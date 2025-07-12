<?php
session_start();
include 'db.php';
include 'navbareddit.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'stammesfuehrung') {
    header("Location: login.php");
    exit();
}

// Benutzer löschen
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: manage_users.php");
    exit();
}

// Rolle ändern
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['user_id'], $_POST['new_role'])) {
    $user_id = intval($_POST['user_id']);
    $new_role = $_POST['new_role'];

    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $new_role, $user_id);
    $stmt->execute();
    header("Location: manage_users.php");
    exit();
}

// Alle Benutzer abrufen
$result = $conn->query("SELECT id, username, role, group_id FROM users");
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Benutzerverwaltung</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>


    <div class="container">
    <h1>Benutzerverwaltung</h1>
	<div class="calendar-table-container">
    <table class="calendar-table">
	
        <thead>
            <tr>
                <th>ID</th>
                <th>Benutzername</th>
                <th>Rolle</th>
                <th>Gruppe</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td>
                        <form method="POST" class="role-form">
                            <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                            <select name="new_role">
                                <option value="">(leer)</option>
                                <option value="leitung" <?= $row['role'] === 'leitung' ? 'selected' : '' ?>>Leitung</option>
                                <option value="stammesfuehrung" <?= $row['role'] === 'stammesfuehrung' ? 'selected' : '' ?>>Stammesführung</option>
                            </select>
                            <button type="submit">Ändern</button>
                        </form>
                    </td>
                    <td><?= htmlspecialchars($row['group_id']) ?></td>
                    <td>
                        <button class="delete-btn" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Benutzer wirklich löschen?');">Löschen</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</div>
</body>
</html>
