<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender - Troja Toscana</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php
session_start();
include 'db.php';
include 'navbar.php';
?>

<div class="container">
    <h1>Kalender</h1>
	<div class="calendar-table-container">
    <table class="calendar-table">
        <thead>
            <tr>
                <th>Titel</th>
                <th>Datum</th>
                <th>Uhrzeit</th>
                <th>Abholdatum</th>
                <th>Abholuhrzeit</th>
                <th>Beschreibung</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM calendar ORDER BY date, time, pickup_date, pickup_time, description");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                echo "<td>" . htmlspecialchars($row['pickup_date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['pickup_time']) . "</td>";
                echo "<td>" . nl2br(htmlspecialchars($row['description'])) . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</div>

</div>
</body>
</html>
