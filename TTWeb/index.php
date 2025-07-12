<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Troja Toscana Homepage</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php
session_start();
include 'db.php';
include 'navbar.php';
?>

<div class="container">
    <h1>Willkommen bei Troja Toscana</h1>

    <?php
    $result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='post'>";
            echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";

            // Bilder anzeigen (maximal 4)
            for ($i = 1; $i <= 4; $i++) {
                $imageField = 'image' . $i;
                if (!empty($row[$imageField])) {
                    $imagePath = htmlspecialchars($row[$imageField]);

                    if (file_exists($imagePath)) {
                        echo "<img src='$imagePath' alt='Bild $i' class='post-image'>";
                    }
                }
            }

            echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
            echo "<p class='post-date'><strong>Veröffentlicht am:</strong> " . date("d.m.Y", strtotime($row['created_at'])) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>Keine Beiträge gefunden.</p>";
    }
    $conn->close();
    ?>
</div>
