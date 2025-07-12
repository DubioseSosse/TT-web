<nav>
    <a href="index.php">Startseite</a>
	<a href="groups.php">Gruppen</a>
    <a href="calendar.php">Kalender</a>
    <a href="gallerie.php">Gallerie</a>
    <a href="info.php">Infos</a>
    <a href="contact.php">Kontakt</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'stammesfuehrung' || $_SESSION['role'] == 'leitung')): ?>
            <a href="dashboard.php">Edit-Bereich</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a>
    <?php endif; ?>
</nav>
