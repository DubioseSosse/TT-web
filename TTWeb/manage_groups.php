<?php
session_start();
include 'db.php';
include 'navbareddit.php';

if ($_SESSION['role'] != 'stammesfuehrung') {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['new_group_name'])) {
        $name = $_POST['new_group_name'];
        $type = $_POST['type'];
        $stmt = $conn->prepare("INSERT INTO groups (name, type) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $type);
        $stmt->execute();
        echo "Gruppe erstellt!";
    }

    if (isset($_POST['delete_group_id'])) {
        $group_id = $_POST['delete_group_id'];
        $stmt = $conn->prepare("DELETE FROM groups WHERE id = ?");
        $stmt->bind_param("i", $group_id);
        $stmt->execute();	
        echo "Gruppe gelöscht!";
    }
}

$result = $conn->query("SELECT id, name FROM groups");
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gruppen verwalten</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <h2>Gruppen verwalten</h2>
    <form method="POST">
        Name: <input type="text" name="new_group_name" required>
        Typ: <select name="type">
            <option value="Meute">Meute</option>
            <option value="Sippe">Sippe</option>
        </select>
        <button type="submit">Neue Gruppe erstellen</button>
    </form>

    <h3>Bestehende Gruppen</h3>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <p><?php echo htmlspecialchars($row['name']); ?>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="delete_group_id" value="<?php echo $row['id']; ?>">
            <button type="submit">Löschen</button>
        </form>
        </p>
    <?php } ?>
	</div>
</body>
</html>