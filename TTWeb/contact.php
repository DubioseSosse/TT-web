<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakt - Troja Toscana</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php
session_start();
include 'db.php';
include 'navbar.php';
?>
<div class="container">
    <h1>Kontakt</h1>
    <div class="contact-content">
        <?php
        $result = $conn->query("SELECT content FROM pages WHERE title = 'contact'");
        $row = $result->fetch_assoc();
        echo $row['content'];
        ?>
    </div>
</div>
</body>
</html>
