<?php
session_start();
include 'db.php';
include 'navbareddit.php';


if ($_SESSION['role'] !== 'stammesfuehrung') {
    die("Zugriff verweigert!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $passwort = password_hash($_POST['passwort'], PASSWORD_DEFAULT); // Passwort hashen
    $role = $_POST['role'];
    $group_id = $_POST['xgroup_id'];

    if ($group_id === null) {
    $stmt = $conn->prepare("INSERT INTO users (username, password, role, group_id) VALUES (?, ?, ?, NULL)");
    $stmt->bind_param("sss", $username, $passwort, $role);
} else {
    $stmt = $conn->prepare("INSERT INTO users (username, password, role, group_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $username, $passwort, $role, $group_id);
}

$stmt->execute();
header("Location: add_user.php");
exit();



}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benutzer hinzufügen</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h1>Benutzer hinzufügen</h1>
    <form method="POST" action="add_user.php">
        <label for="username">Benutzername:</label>
        <input type="text" name="username" id="username" required>

        <label for="passwort">Passwort:</label>
        <input type="password" name="passwort" id="passwort" required>

        <label for="role">Rolle (leitung, stammesfuehrung oder leer):</label>
        <input type="text" name="role" id="role">

        <label for="xgroup_id">GruppenID:</label>
        <input type="number" name="xgroup_id" id="xgroup_id">

        <button type="submit">Speichern</button>
    </form>

    <h2>Verfügbare Gruppen:</h2>
    <?php
    $result = $conn->query("SELECT id, name FROM groups");
    while ($row = $result->fetch_assoc()) {
        echo "<p>" . htmlspecialchars($row['id']) . " – " . htmlspecialchars($row['name']) . "</p>";
    }
    ?>
	</div>
</body>
</html>
