<?php
session_start();
include 'db.php';
include 'navbar.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$xgroup_id = $_SESSION['xgroup_id'] ?? null;

$upload_success = null;
$error_message = null;

// Bild löschen
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);

    // Hole Bilddaten zur Validierung
    $stmt = $conn->prepare("SELECT filename, group_id, uploaded_by FROM images WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->bind_result($filename, $group_id, $uploaded_by);
    $stmt->fetch();
    $stmt->close();

    // Prüfen ob Löschung erlaubt ist
    $allowed = false;
    if ($role === 'stammesfuehrung') {
        $allowed = true;
    } elseif ($role === 'leitung' && $xgroup_id !== null && $group_id == $xgroup_id) {
        $allowed = true;
    }

    if ($allowed) {
        unlink("uploads/images/" . $filename);
        $stmt = $conn->prepare("DELETE FROM images WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $upload_success = "Bild gelöscht.";
    } else {
        $error_message = "Keine Berechtigung zum Löschen dieses Bildes.";
    }
}

// Bild hochladen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image'])) {
    if ($role === 'stammesfuehrung' || ($role === 'leitung' && $xgroup_id !== null)) {
        $description = $_POST['description'] ?? '';
        $group_id = null;

        if ($role === 'stammesfuehrung') {
            $group_id = $_POST['group_id'] !== '' ? intval($_POST['group_id']) : null;
        } else {
            $group_id = $xgroup_id;
        }

        $upload_dir = "uploads/images/";
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file = $_FILES['image'];

        if ($file['error'] === UPLOAD_ERR_OK) {
            $file_type = mime_content_type($file['tmp_name']);
            $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (in_array($file_type, $allowed_types)) {
                $new_filename = uniqid('img_', true) . "." . $file_ext;
                $target_path = $upload_dir . $new_filename;

                if (move_uploaded_file($file['tmp_name'], $target_path)) {
                    $stmt = $conn->prepare("INSERT INTO images (filename, description, group_id, uploaded_by) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssii", $new_filename, $description, $group_id, $user_id);
                    $stmt->execute();
                    $upload_success = "Bild erfolgreich hochgeladen.";
                } else {
                    $error_message = "Fehler beim Speichern der Datei.";
                }
            } else {
                $error_message = "Ungültiger Dateityp.";
            }
        } else {
            $error_message = "Fehler beim Hochladen.";
        }
    }
}

// Galerie laden
if ($role === 'stammesfuehrung') {
    $stmt = $conn->prepare("SELECT id, filename, description, group_id, uploaded_by FROM images ORDER BY uploaded_at DESC");
} else {
    $stmt = $conn->prepare("SELECT id, filename, description, group_id, uploaded_by FROM images WHERE group_id IS NULL OR group_id = ? ORDER BY uploaded_at DESC");
    $stmt->bind_param("i", $xgroup_id);
}
$stmt->execute();
$result = $stmt->get_result();

// Gruppen laden (für Dropdown bei Stammesführung)
$groups = $conn->query("SELECT id, name FROM groups");
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Bildergalerie</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .gallery-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .gallery-item {
            border: 1px solid #ccc;
            padding: 10px;
            width: 200px;
            text-align: center;
        }

        .gallery-item img {
            max-width: 100%;
            height: auto;
        }

        .upload-form {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 30px;
            max-width: 500px;
        }
    </style>
</head>
<body>


<div class="container">
    <h1>Bildergalerie</h1>

    <?php if ($upload_success): ?>
        <p style="color: green;"><?= htmlspecialchars($upload_success) ?></p>
    <?php elseif ($error_message): ?>
        <p style="color: red;"><?= htmlspecialchars($error_message) ?></p>
    <?php endif; ?>

    <?php if ($role === 'stammesfuehrung' || $role === 'leitung'): ?>
        <form method="POST" enctype="multipart/form-data" class="upload-form">
            <h2>Bild hochladen</h2>

            <label for="image">Bilddatei:</label><br>
            <input type="file" name="image" accept="image/*" required><br><br>

            <label for="description">Beschreibung:</label><br>
            <textarea name="description" rows="3"></textarea><br><br>

            <?php if ($role === 'stammesfuehrung'): ?>
                <label for="group_id">Gruppe (optional):</label><br>
                <select name="group_id">
                    <option value="">Allgemein</option>
                    <?php while ($row = $groups->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                    <?php endwhile; ?>
                </select><br><br>
            <?php endif; ?>

            <button type="submit">Bild hochladen</button>
        </form>
    <?php endif; ?>

    <div class="gallery-container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="gallery-item">
                <img src="uploads/images/<?= htmlspecialchars($row['filename']) ?>" alt="Bild"><br>
                <p><?= htmlspecialchars($row['description']) ?></p>

                <?php
                $canDelete = $role === 'stammesfuehrung' ||
                            ($role === 'leitung' && $xgroup_id !== null && $row['group_id'] == $xgroup_id);
                ?>

                <?php if ($canDelete): ?>
                    <form method="POST" onsubmit="return confirm('Bild wirklich löschen?')">
                        <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                        <button type="submit">🗑️ Löschen</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
