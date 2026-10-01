<?php
session_start();
require_once 'koneksi.php';

if (($_SESSION['role'] ?? '') !== 'admin') {
	header('Location: index.php');
	exit;
}

$fields = ['nisn', 'nis', 'nama', 'id_kelas', 'nama_kelas', 'alamat', 'no_telp', 'id_spp'];
$values = array_fill_keys($fields, '');
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	foreach ($fields as $field) {
		$values[$field] = trim($_POST[$field] ?? '');
	}

	if (in_array('', $values, true)) {
		$error = 'Semua kolom wajib diisi.';
	} else {
		$stmt = mysqli_prepare(
			$conn,
			'INSERT INTO tb_siswa (nisn, nis, nama, id_kelas, nama_kelas, alamat, no_telp, id_spp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
		);

		if ($stmt) {
			mysqli_stmt_bind_param(
				$stmt,
				'ssssssss',
				$values['nisn'],
				$values['nis'],
				$values['nama'],
				$values['id_kelas'],
				$values['nama_kelas'],
				$values['alamat'],
				$values['no_telp'],
				$values['id_spp']
			);

			if (mysqli_stmt_execute($stmt)) {
				mysqli_stmt_close($stmt);
				mysqli_close($conn);
				header('Location: datasiswa.php?status=added');
				exit;
			}

			$error = mysqli_stmt_errno($stmt) === 1062
				? 'NISN atau NIS sudah terdaftar.'
				: 'Data siswa gagal disimpan. Periksa kembali data dan database.';
			mysqli_stmt_close($stmt);
		} else {
			$error = 'Form belum dapat diproses. Periksa struktur tabel tb_siswa.';
		}
	}
}

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
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Tambah Siswa - Aplikasi SPP</title>
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
					<li class="nav-item"><a class="nav-link" href="pembayaran.php">Pembayaran</a></li>
					<li class="nav-item"><a class="nav-link" href="cekpembayaran.php">Cek Pembayaran</a></li>
					<li class="nav-item"><a class="nav-link" href="detailpembayaran.php">Detail Pembayaran</a></li>
				</ul>
			</nav>
		</aside>

		<main class="flex-grow-1 p-4 p-lg-5">
			<header class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
				<div>
					<h1 class="h3 mb-1">Tambah Data Siswa</h1>
					<p class="text-secondary mb-0">Masukkan data siswa baru</p>
				</div>
				<a href="datasiswa.php" class="btn btn-outline-secondary">Kembali</a>
			</header>

			<section class="bg-white border rounded-2 p-4" aria-label="Form tambah siswa">
				<?php if ($error !== ''): ?>
					<div class="alert alert-danger" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
				<?php endif; ?>

				<form method="post" action="tambahsiswa.php">
					<div class="row g-3">
						<?php foreach ($fields as $field): ?>
							<div class="col-md-6">
								<label for="<?= $field ?>" class="form-label"><?= htmlspecialchars($labels[$field], ENT_QUOTES, 'UTF-8') ?></label>
								<?php if ($field === 'alamat'): ?>
									<textarea class="form-control" id="<?= $field ?>" name="<?= $field ?>" rows="2" required><?= htmlspecialchars($values[$field], ENT_QUOTES, 'UTF-8') ?></textarea>
								<?php else: ?>
									<input class="form-control" type="<?= $field === 'no_telp' ? 'tel' : 'text' ?>" id="<?= $field ?>" name="<?= $field ?>" value="<?= htmlspecialchars($values[$field], ENT_QUOTES, 'UTF-8') ?>" required>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="d-flex gap-2 mt-4">
						<button type="submit" class="btn btn-primary">Simpan</button>
						<a href="datasiswa.php" class="btn btn-outline-secondary">Batal</a>
					</div>
				</form>
			</section>
		</main>
	</div>
</body>
</html>
<?php mysqli_close($conn); ?>
