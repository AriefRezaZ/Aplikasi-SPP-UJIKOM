<?php
session_start();
if (($_SESSION['role'] ?? '') !== 'admin') {
	header('Location: index.php');
	exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || trim($_POST['id_petugas'] ?? '') === '') {
	header('Location: datapetugas.php');
	exit;
}

require_once 'koneksi.php';
$id_petugas = trim($_POST['id_petugas']);
$stmt = mysqli_prepare($conn, 'DELETE FROM tb_petugas WHERE id_petugas = ?');

if ($stmt) {
	mysqli_stmt_bind_param($stmt, 's', $id_petugas);
	$deleted = mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
	header('Location: datapetugas.php?status=' . ($deleted ? 'deleted' : 'delete_failed'));
	exit;
}

mysqli_close($conn);
header('Location: datapetugas.php?status=delete_failed');
exit;
