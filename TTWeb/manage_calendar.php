<?php
session_start();
include 'db.php';
include 'navbareddit.php';

// Nur Stammesführung hat Zugriff
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'stammesfuehrung') {
    header("Location: login.php");
    exit();
}

// Eintrag löschen
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM calendar WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: manage_calendar.php");
    exit();
}

// Alle Kalendereinträge abrufen
$result = $conn->query("SELECT id, title FROM calendar ORDER BY id");
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Kalenderverwaltung</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <h1>Kalendereinträge verwalten</h1>

    <table class="calendar-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titel</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td>
                    <button href="?delete=<?= $row['id'] ?>" onclick="return confirm('Kalendereintrag wirklich löschen?');" style="color:red;">Löschen</button>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
