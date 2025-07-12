<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infos - Troja Toscana</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php
session_start();
include 'db.php';
include 'navbar.php';
?>
 
<div class="container">
    <h1>Infos</h1>

    <div class="info-content">
        <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        
        $result = $conn->query("SELECT content FROM pages WHERE title = 'info'");
        if ($result) {
            $row = $result->fetch_assoc();
            echo $row['content'];
        } else {
            echo "Fehler bei der Abfrage: " . $conn->error;
        }
        ?>
    </div>
	</div>
</body>
</html>
