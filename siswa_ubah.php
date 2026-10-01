<?php
session_start();
if (($_SESSION['role'] ?? '') !== 'admin') {
	header('Location: index.php');
	exit;
}

require_once 'koneksi.php';

$fields = ['nisn', 'nis', 'nama', 'id_kelas', 'nama_kelas', 'alamat', 'no_telp', 'id_spp'];
$labels = [
	'nisn' => 'NISN',
	'nis' => 'NIS',
	'nama' => 'Nama siswa',
	'id_kelas' => 'ID kelas',
	'nama_kelas' => 'Nama kelas',
	'alamat' => 'Alamat',
	'no_telp' => 'Nomor telepon',
	'id_spp' => 'ID SPP',
];
$nisn = trim($_POST['nisn'] ?? $_GET['nisn'] ?? '');
$student = array_fill_keys($fields, '');
$error = '';

if ($nisn === '') {
	header('Location: datasiswa.php');
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	foreach (array_slice($fields, 1) as $field) {
		$student[$field] = trim($_POST[$field] ?? '');
	}
	$student['nisn'] = $nisn;

	if (in_array('', $student, true)) {
		$error = 'Semua kolom wajib diisi.';
	} else {
		$stmt = mysqli_prepare($conn, 'UPDATE tb_siswa SET nis = ?, nama = ?, id_kelas = ?, nama_kelas = ?, alamat = ?, no_telp = ?, id_spp = ? WHERE nisn = ?');
		if ($stmt) {
			mysqli_stmt_bind_param($stmt, 'ssssssss', $student['nis'], $student['nama'], $student['id_kelas'], $student['nama_kelas'], $student['alamat'], $student['no_telp'], $student['id_spp'], $nisn);
			if (mysqli_stmt_execute($stmt)) {
				mysqli_stmt_close($stmt);
				mysqli_close($conn);
				header('Location: datasiswa.php?status=updated');
				exit;
			}
			$error = mysqli_stmt_errno($stmt) === 1062 ? 'NIS sudah digunakan siswa lain.' : 'Data siswa gagal diperbarui.';
			mysqli_stmt_close($stmt);
		} else {
			$error = 'Form belum dapat diproses. Periksa struktur tabel tb_siswa.';
		}
	}
} else {
	$stmt = mysqli_prepare($conn, 'SELECT nisn, nis, nama, id_kelas, nama_kelas, alamat, no_telp, id_spp FROM tb_siswa WHERE nisn = ?');
	if ($stmt) {
		mysqli_stmt_bind_param($stmt, 's', $nisn);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$student = mysqli_fetch_assoc($result) ?: $student;
		mysqli_stmt_close($stmt);
	}
	if ($student['nisn'] === '') {
		mysqli_close($conn);
		header('Location: datasiswa.php?status=notfound');
		exit;
	}
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit Siswa - Aplikasi SPP</title>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<style>
		body { min-height: 100vh; background: #f4f6f5; }
		.sidebar { width: 250px; min-height: 100vh; background: #173b35; }
		.sidebar .nav-link { color: #d7e4df; border-radius: 6px; padding: 10px 12px; }
		.sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: #28574e; }
		@media (max-width: 767.98px) {
			.sidebar { width: 100%; min-height: auto; }
			.sidebar .nav { flex-direction: row !important; flex-wrap: wrap; }
		}
	</style>
</head>
<body>
	<div class="d-md-flex min-vh-100">
		<aside class="sidebar p-3">
			<a href="dashboard_admin.php" class="d-block text-white text-decoration-none fs-5 fw-semibold mb-4 px-2">Aplikasi SPP</a>
			<nav aria-label="Navigasi utama">
				<ul class="nav nav-pills flex-column gap-1">
					<li class="nav-item"><a class="nav-link" href="dashboard_admin.php">Dashboard</a></li>
					<li class="nav-item"><a class="nav-link active" href="datasiswa.php" aria-current="page">Data Siswa</a></li>
					<li class="nav-item"><a class="nav-link" href="datakelas.php">Data Kelas</a></li>
					<li class="nav-item"><a class="nav-link" href="datapetugas.php">Data Petugas</a></li>
					<li class="nav-item"><a class="nav-link" href="datapembayaran.php">Pembayaran</a></li>
					<li class="nav-item"><a class="nav-link" href="cekpembayaran.php">Cek Pembayaran</a></li>
					<li class="nav-item"><a class="nav-link" href="detaildatapembayaran.php">Detail Pembayaran</a></li>
				</ul>
			</nav>
		</aside>
		<main class="flex-grow-1 p-4 p-lg-5">
			<header class="d-flex justify-content-between align-items-center gap-3 mb-4">
				<h1 class="h3 mb-0">Edit Data Siswa</h1>
				<a href="datasiswa.php" class="btn btn-outline-secondary">Kembali</a>
			</header>
			<section class="bg-white border rounded-2 p-4">
				<?php if ($error !== ''): ?>
					<div class="alert alert-danger" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
				<?php endif; ?>
				<form method="post" action="siswa_ubah.php">
					<input type="hidden" name="nisn" value="<?= htmlspecialchars($nisn, ENT_QUOTES, 'UTF-8') ?>">
					<div class="row g-3">
						<?php foreach ($fields as $field): ?>
							<div class="col-md-6">
								<label for="<?= $field ?>" class="form-label"><?= htmlspecialchars($labels[$field], ENT_QUOTES, 'UTF-8') ?></label>
								<?php if ($field === 'alamat'): ?>
									<textarea class="form-control" id="<?= $field ?>" name="<?= $field ?>" rows="2" required><?= htmlspecialchars((string) $student[$field], ENT_QUOTES, 'UTF-8') ?></textarea>
								<?php else: ?>
									<input class="form-control" type="<?= $field === 'no_telp' ? 'tel' : 'text' ?>" id="<?= $field ?>" name="<?= $field ?>" value="<?= htmlspecialchars((string) $student[$field], ENT_QUOTES, 'UTF-8') ?>" <?= $field === 'nisn' ? 'readonly' : 'required' ?>>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="d-flex gap-2 mt-4">
						<button type="submit" class="btn btn-primary">Simpan Perubahan</button>
						<a href="datasiswa.php" class="btn btn-outline-secondary">Batal</a>
					</div>
				</form>
			</section>
		</main>
	</div>
</body>
</html>
<?php mysqli_close($conn); ?>
