<?php
session_start();
include 'db.php';
include 'navbareddit.php';


if ($_SESSION['role'] !== 'stammesfuehrung') {
    die("Zugriff verweigert!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $stmt = $conn->prepare("INSERT INTO calendar (title, description, date, time) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $description, $date, $time);
    $stmt->execute();

    header("Location: calendar.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalendertermin hinzufügen</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
	<p> 
    <h1>Kalendertermin hinzufügen</h1>
    <form method="POST" action="add_event.php">
        <label for="title">Titel:</label>
        <input type="text" name="title" id="title" required>
        <label for="description">Beschreibung:</label>
        <textarea name="description" id="description" rows="5" required></textarea>
        <label for="date">Datum:</label>
        <input type="date" name="date" id="date" required>
        <label for="time">Uhrzeit:</label>
        <input type="time" name="time" id="time" required>
        <button type="submit">Speichern</button>
    </form>
</div>
</body>
</html>
