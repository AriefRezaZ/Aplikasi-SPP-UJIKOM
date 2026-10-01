<?php
session_start();
if (($_SESSION['role'] ?? '') !== 'admin') {
	header('Location: index.php');
	exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || trim($_POST['id_kelas'] ?? '') === '') {
	header('Location: datakelas.php');
	exit;
}

require_once 'koneksi.php';
$id_kelas = trim($_POST['id_kelas']);
$stmt = mysqli_prepare($conn, 'DELETE FROM tb_kelas WHERE id_kelas = ?');

if ($stmt) {
	mysqli_stmt_bind_param($stmt, 's', $id_kelas);
	$deleted = mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
	header('Location: datakelas.php?status=' . ($deleted ? 'deleted' : 'delete_failed'));
	exit;
}

mysqli_close($conn);
header('Location: datakelas.php?status=delete_failed');
exit;
