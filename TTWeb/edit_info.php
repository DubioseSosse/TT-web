<?php
session_start();
include 'db.php';
include 'navbareddit.php';


if ($_SESSION['role'] !== 'stammesfuehrung') {
    die("Zugriff verweigert!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];

    $stmt = $conn->prepare("UPDATE pages SET content = ? WHERE title = 'info'");
    $stmt->bind_param("s", $content);
    $stmt->execute();

    header("Location: info.php");
    exit();
}

$result = $conn->query("SELECT content FROM pages WHERE title = 'info'");
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infos bearbeiten</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <h1>Infos bearbeiten</h1>
    <form method="POST" action="edit_info.php">
        <textarea name="content" rows="10"><?php echo $row['content']; ?></textarea>
        <button type="submit">Speichern</button>
    </form>
	</div>
</body>
</html>
