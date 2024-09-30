<?php
session_start();

// Inisialisasi session tasks jika belum ada
if (!isset($_SESSION['tasks'])) {
	$_SESSION['tasks'] = [];
}

// Tambah task
if (isset($_POST['add'])) {
	$task = [
		'id' => uniqid(),  // ID unik untuk setiap task
		'tasklabel' => $_POST['task'],
		'taskstatus' => 'open'
	];
	$_SESSION['tasks'][] = $task;
	header('Location: index.php');
	exit();
}

// Hapus task
if (isset($_GET['delete'])) {
	$_SESSION['tasks'] = array_filter($_SESSION['tasks'], function ($task) {
		return $task['id'] !== $_GET['delete'];
	});
	header('Location: index.php');
	exit();
}

// Ubah status task
if (isset($_GET['done'])) {
	foreach ($_SESSION['tasks'] as &$task) {
		if ($task['id'] === $_GET['done']) {
			$task['taskstatus'] = ($task['taskstatus'] === 'open') ? 'close' : 'open';
			break;
		}
	}
	header('Location: index.php');
	exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>To Do List</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
	<style>
		body {
			background: #8f94fb;
			/* Background gradient */
		}

		.container {
			margin-top: 50px;
		}

		.task-done {
			text-decoration: line-through;
			color: grey;
		}

		.animation-bar {
			width: 100%;
			height: 5px;
			/* Tinggi bar animasi */
			background: #ffd700;
			/* Warna bar */
			position: relative;
			animation: slide 2s infinite;
			/* Menjalankan animasi */
		}

		@keyframes slide {
			0% {
				left: -100%;
			}

			50% {
				left: 100%;
			}

			100% {
				left: -100%;
			}
		}
	</style>
</head>

<body>
	<div class="container">
		<h2 class="text-center text-white">To Do List</h2>
		<div class="animation-bar"></div> <!-- Animasi Bar -->

		<!-- Form untuk menambah task -->
		<form method="POST" class="my-4">
			<div class="input-group mb-3">
				<input type="text" name="task" class="form-control" placeholder="Add task" required>
				<button class="btn btn-primary" type="submit" name="add">Add Task</button>
			</div>
		</form>

		<!-- Daftar tasks -->
		<ul class="list-group">
			<?php if (!empty($_SESSION['tasks'])): ?>
				<?php foreach ($_SESSION['tasks'] as $task): ?>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						<div>
							<input type="checkbox" <?= $task['taskstatus'] === 'close' ? 'checked' : '' ?>
								onclick="window.location.href = '?done=<?= $task['id'] ?>'">
							<span class="<?= $task['taskstatus'] === 'close' ? 'task-done' : '' ?>">
								<?= htmlspecialchars($task['tasklabel']) ?>
							</span>
						</div>
						<div>
							<a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
								onclick="loadTaskData('<?= $task['id'] ?>', '<?= htmlspecialchars($task['tasklabel']) ?>')">Edit</a>
							<a href="?delete=<?= $task['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
						</div>
					</li>
				<?php endforeach; ?>
			<?php else: ?>
				<li class="list-group-item">No tasks available.</li>
			<?php endif; ?>
		</ul>
	</div>

	<!-- Modal untuk Edit Task -->
	<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editModalLabel">Edit Task</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form action="edit.php" method="POST">
						<div class="mb-3">
							<label for="taskLabel" class="form-label">Task</label>
							<input type="hidden" id="taskId" name="task_id">
							<input type="text" class="form-control" id="taskLabel" name="task" required>
						</div>
						<div class="text-end">
							<button type="submit" class="btn btn-primary" name="edit">Save changes</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Bootstrap JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

	<script>
		// Function to load task data into the modal form
		function loadTaskData(id, label) {
			document.getElementById('taskId').value = id;
			document.getElementById('taskLabel').value = label;
		}
	</script>
</body>

</html>