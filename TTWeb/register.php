<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $role = $_POST["role"]; // "gruppenleitung" oder "stammesführung"
	$user = "root";
	$server = "localhost";
	$pw = "";
	$db = "troja_toscana";

    // Passwort sicher verschlüsseln
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
	$con = mysqli_connect($server,$user,$pw);
	mysqli_select_db($con,$db);
	mysqli_set_charset($con, "utf8");
	
	$sql = "INSERT INTO users (username, password_hash, role) VALUES ($username, $password_hash, $role)";
	$abfrage = mysqli_query($con,$sql);
	if(!$abfrage)
	{
		echo "<p>Fehler der SQL-Abfrage".mysqli_error($con);
	}else{
		echo "Registrierung erfolgreich! <a href='login.php'>Jetzt einloggen</a>";
	}
	mysqli_close($con);
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Registrierung</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
    </nav>

    <div class="container">
        <h2>Registrierung</h2>
        <form method="POST">
            <label>Benutzername:</label>
            <input type="text" name="username" required>

            <label>Passwort:</label>
            <input type="password" name="password" required>

            <label>Rolle:</label>
            <select name="role">
                <option value="gruppenleitung">Gruppenleitung</option>
                <option value="stammesführung">Stammesführung</option>
            </select>

            <button type="submit">Registrieren</button>
        </form>
    </div>
</body>
</html>
