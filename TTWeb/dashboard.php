<?php
session_start();
include 'navbareddit.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eddit Bereich</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>



<div class="container">
    <h2>Willkommen im Eddit-Bereich</h2>
    <p>Hallo, <?php echo htmlspecialchars($_SESSION['username']); ?>! Du bist als <b><?php echo $_SESSION['role']; ?></b> eingeloggt.</p>
</div>

</body>
</html>
