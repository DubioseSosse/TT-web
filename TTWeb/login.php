<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Nutzer aus Datenbank holen, inklusive group_id
    $stmt = $conn->prepare("SELECT id, password, role, group_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password_hash, $role, $group_id);
        $stmt->fetch();

        // Passwort prüfen
        if (password_verify($password, $password_hash)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $role;
            $_SESSION["group_id"] = is_null($group_id) ? null : $group_id;
            $_SESSION["hash_password"] = $password_hash;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Falsches Passwort!";
        }
    } else {
        $error = "Benutzer nicht gefunden!";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<nav>
    <a href="index.php">Home</a>
	<a href="login.php">Login</a>
    
</nav>

<div class="container">
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>
    
    <form method="POST">
        <label>Benutzername:</label>
        <input type="text" name="username" required>

        <label>Passwort:</label>
        <input type="password" name="password" required>

        <button type="submit">Anmelden</button>
    </form>
</div>

</body>
</html>
