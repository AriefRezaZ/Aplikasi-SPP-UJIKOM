<?php
session_start();
if (($_SESSION['role'] ?? '') !== 'admin') {
	header('Location: index.php');
	exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || trim($_POST['nisn'] ?? '') === '') {
	header('Location: datasiswa.php');
	exit;
}

require_once 'koneksi.php';
$nisn = trim($_POST['nisn']);
$stmt = mysqli_prepare($conn, 'DELETE FROM tb_siswa WHERE nisn = ?');

if ($stmt) {
	mysqli_stmt_bind_param($stmt, 's', $nisn);
	$deleted = mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
	header('Location: datasiswa.php?status=' . ($deleted ? 'deleted' : 'delete_failed'));
	exit;
}

mysqli_close($conn);
header('Location: datasiswa.php?status=delete_failed');
exit;
