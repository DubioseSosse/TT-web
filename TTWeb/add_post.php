<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'stammesfuehrung') {
    header("Location: login.php");
    exit();
}

// Verarbeitung des Formulars
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $uploadedImages = [];

    // Bilder hochladen
    for ($i = 1; $i <= 4; $i++) {
        $imageField = 'image' . $i;
        if (isset($_FILES[$imageField]) && $_FILES[$imageField]['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES[$imageField]['tmp_name'];
            $fileName = basename($_FILES[$imageField]['name']);
            $uploadPath = 'uploads/' . time() . '_' . $fileName;
            if (move_uploaded_file($tmpName, $uploadPath)) {
                $uploadedImages[] = $uploadPath;
            } else {
                $uploadedImages[] = null;
            }
        } else {
            $uploadedImages[] = null;
        }
    }

    // SQL vorbereiten
    $stmt = $conn->prepare("INSERT INTO posts (title, content, image1, image2, image3, image4) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ssssss",
        $title,
        $content,
        $uploadedImages[0],
        $uploadedImages[1],
        $uploadedImages[2],
        $uploadedImages[3]
    );

    if ($stmt->execute()) {
        echo "<p>Post erfolgreich erstellt!</p>";
    } else {
        echo "<p>Fehler beim Speichern des Posts.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Neuen Post erstellen</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'navbareddit.php'; ?>

<div class="container">
    <h1>Neuen Post erstellen</h1>
    <form action="add_post.php" method="POST" enctype="multipart/form-data">
        <label for="title">Überschrift:</label><br>
        <input type="text" id="title" name="title" required><br><br>

        <label for="content">Text:</label><br>
        <textarea id="content" name="content" rows="6" required></textarea><br><br>

        <label>Bilder (optional, max. 4):</label><br>
        <input type="file" name="image1" accept="image/*"><br>
        <input type="file" name="image2" accept="image/*"><br>
        <input type="file" name="image3" accept="image/*"><br>
        <input type="file" name="image4" accept="image/*"><br><br>

        <button type="submit">Post veröffentlichen</button>
    </form>
</div>
</body>
</html>
