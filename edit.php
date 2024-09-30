<?php
session_start();

// Proses edit task
if (isset($_POST['edit'])) {
	foreach ($_SESSION['tasks'] as &$task) {
		if ($task['id'] == $_POST['task_id']) {
			$task['tasklabel'] = $_POST['task'];
			break;
		}
	}
	// Redirect kembali ke index.php setelah proses edit
	header('Location: index.php');
}
