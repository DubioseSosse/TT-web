
<?php

$host = "localhost";
$user = "root"; // Dein MySQL-Benutzername
$pass = ""; // Dein MySQL-Passwort (bei XAMPP oft leer)
$dbname = "troja_toscana";

$conn = new mysqli($host, $user, $pass, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}
?>
