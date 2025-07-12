<nav>
    <a href="index.php">Home</a>
    
    <?php if ($_SESSION['role'] == 'stammesfuehrung'): ?>
        <a href="manage_groups.php">Gruppen verwalten</a>
		<a href="edit_group.php">Meine Gruppe bearbeiten</a>
		<a href="edit_contact.php">Kontakt bearbeiten</a>
		<a href="edit_info.php">Info bearbeiten</a>
		<a href="add_event.php">Kalendereintrag Hinzufügen</a>
		<a href="manage_calendar.php">Kalendereinträge verwalten</a>
		<a href="manage_users.php">Benutzer verwalten</a>
		<a href="add_user.php">Benutzer Hinzufügen</a>
		<a href="manage_posts.php">Post verwalten</a>
		<a href="add_post.php">Post Hinzufügen</a>
    <?php endif; ?>
	<?php if ($_SESSION['role'] == 'leitung'): ?>
		<a href="edit_group.php">Meine Gruppe bearbeiten</a>
	<?php endif; ?>
    
    
    <a href="logout.php">Abmelden</a>
</nav>