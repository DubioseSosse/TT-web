<?php
session_start();
include 'db.php';

// Nur Admins dürfen zugreifen
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'stammesfuehrung') {
    header("Location: login.php");
    exit();
}

// Post löschen
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $postId = (int)$_GET['delete'];

    // Optional: Bilder löschen
    $stmt = $conn->prepare("SELECT image1, image2, image3, image4 FROM posts WHERE id = ?");
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        for ($i = 1; $i <= 4; $i++) {
            $img = $row["image$i"];
            if (!empty($img) && file_exists($img)) {
                unlink($img); // Bilddatei löschen
            }
        }
    }
    $stmt->close();

    // Post aus DB löschen
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $stmt->close();

    echo "<p>Post gelöscht.</p>";
}

// Alle Posts abrufen
$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Posts verwalten</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'navbareddit.php'; ?>
<div class="container">
<h1>Beiträge verwalten</h1>
<div class="calendar-table-container">
    

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="calendar-table">
            <tr>
                <th>ID</th>
                <th>Überschrift</th>
                <th>Datum</th>
                <th>Aktionen</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= date("d.m.Y", strtotime($row['created_at'])) ?></td>
                    <td>
                        <a href="manage_posts.php?delete=<?= $row['id'] ?>" onclick="return confirm('Diesen Post wirklich löschen?')" class="btn btn-danger">Löschen</a>

                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
		</div>
    <?php else: ?>
        <p>Keine Beiträge gefunden.</p>
    <?php endif; ?>

</div>
</body>
</html>

<?php $conn->close(); ?>
