<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Troja Toscana Gruppen</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php
session_start();
include 'db.php';
include 'navbar.php';

?>

<div class="container">
    <h1>Unsere Gruppen</h1>

    <?php
    $result = $conn->query("SELECT * FROM groups");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
    echo "<div class='group'>";
    echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";

    $imagePath = "images/" . htmlspecialchars($row['image'] ?? ''); // Falls 'image' nicht existiert, wird ein leerer String verwendet

    if (!empty($row['image']) && file_exists($imagePath)) {
        echo "<img src='$imagePath' alt='Gruppenbild'><br>";
    } else {
        echo "<p>Bild momentan nicht vorhanden</p>";
    }

    echo "<p>" . nl2br(htmlspecialchars($row['description'])) . "</p>";
    echo "<p><strong>Treffen:</strong> " . htmlspecialchars($row['meeting_times']) . "</p>";
    echo "<p><strong>Typ:</strong> " . htmlspecialchars($row['type']) . "</p>";
    echo "</div>";
}
} else {
    echo "<p>Keine Gruppen gefunden oder Fehler bei der Datenbankabfrage.</p>";
}$conn->close();


    ?>
</div>

</body>
</html>
