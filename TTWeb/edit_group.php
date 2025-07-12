<?php
session_start();
include 'db.php';
include 'navbareddit.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$user_group_id = $_SESSION['group_id'] ?? null;

// Gruppen-ID aus URL oder für Leitung direkt aus Session
$group_id = $_GET['group_id'] ?? null;

if ($role === 'leitung') {
    // Leitung darf nur ihre eigene Gruppe bearbeiten
    if (!$user_group_id) {
        echo "Keine Gruppe zugewiesen.";
        exit();
    }
    $group_id = $user_group_id;
} elseif ($role === 'stammesfuehrung' && !$group_id) {
    // Stammesführung ohne ausgewählte Gruppe → Übersicht zeigen
    $stmt = $conn->prepare("SELECT id, name FROM groups");
    $stmt->execute();
    $result = $stmt->get_result();
    ?>
    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <title>Gruppenübersicht</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        
        <div class="container">
            <h2>Alle Gruppen</h2>
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li><a href="?group_id=<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></a></li>
                <?php endwhile; ?>
            </ul>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// Wenn keine group_id vorhanden oder ungültig
if (!$group_id) {
    echo "Ungültige Gruppen-ID!";
    exit();
}

// Gruppeninformationen laden
$stmt = $conn->prepare("SELECT name, description, meeting_times, photo FROM groups WHERE id = ?");
$stmt->bind_param("i", $group_id);
$stmt->execute();
$stmt->bind_result($name, $description, $meeting_times, $photo);
if (!$stmt->fetch()) {
    echo "Gruppe nicht gefunden!";
    exit();
}
$stmt->close();

// Formular abgeschickt → Gruppe aktualisieren
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_description = $_POST['description'] ?? '';
    $new_meeting_times = $_POST['meeting_times'] ?? '';
    $new_photo = $photo;

    // Foto-Upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        $file_tmp_name = $_FILES['photo']['tmp_name'];
        $file_name = basename($_FILES['photo']['name']);
        $file_size = $_FILES['photo']['size'];
        $file_type = $_FILES['photo']['type'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_type, $allowed_types) && $file_size <= 5000000) {
            $new_photo = uniqid('', true) . '.' . $file_ext;
            $target_file = $upload_dir . $new_photo;

            if (!move_uploaded_file($file_tmp_name, $target_file)) {
                echo "Fehler beim Hochladen des Fotos.";
            }
        } else {
            echo "Ungültiges Format oder Datei zu groß (max. 5MB, JPG/PNG/GIF).";
        }
    }

    // Aktualisieren
    $stmt = $conn->prepare("UPDATE groups SET description = ?, meeting_times = ?, photo = ? WHERE id = ?");
    $stmt->bind_param("sssi", $new_description, $new_meeting_times, $new_photo, $group_id);

    if ($stmt->execute()) {
        echo "Daten erfolgreich aktualisiert!";
    } else {
        echo "Fehler beim Aktualisieren!";
    }

    $stmt->close();

    // Aktuelle Werte neu laden
    header("Location: edit_group.php?group_id=" . $group_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Gruppe bearbeiten</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<nav>
    <a href="index.php">Home</a>
    <?php if ($role == 'stammesfuehrung'): ?>
        <a href="manage_groups.php">Gruppen verwalten</a>
        <a href="manage_users.php">Benutzer verwalten</a>
        <a href="add_user.php">Benutzer hinzufügen</a>
    <?php endif; ?>
    <a href="logout.php">Abmelden</a>
</nav>

<div class="container">
    <h2>Gruppe bearbeiten: <?= htmlspecialchars($name) ?></h2>

    <form method="POST" enctype="multipart/form-data">
        <label>Beschreibung:</label><br>
        <textarea name="description" required><?= htmlspecialchars($description) ?></textarea><br>

        <label>Gruppenzeiten:</label><br>
        <input type="text" name="meeting_times" value="<?= htmlspecialchars($meeting_times) ?>" required><br>

        <label>Neues Foto hochladen:</label>
        <input type="file" name="photo" accept="image/*"><br>

        <button type="submit">Speichern</button>
    </form>

    <?php if ($photo): ?>
        <h3>Aktuelles Foto:</h3>
        <img src="uploads/<?= htmlspecialchars($photo) ?>" alt="Gruppenfoto" width="200">
    <?php endif; ?>
</div>
</body>
</html>
