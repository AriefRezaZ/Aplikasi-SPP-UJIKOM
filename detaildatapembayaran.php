<?php
require_once 'koneksi.php';

$result = mysqli_query($conn, 'SELECT * FROM tb_pembayaran');
$queryError = $result === false;
$columns = $queryError ? [] : mysqli_fetch_fields($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Detail Pembayaran - Aplikasi SPP</title>
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
					<li class="nav-item"><a class="nav-link" href="datasiswa.php">Data Siswa</a></li>
					<li class="nav-item"><a class="nav-link" href="datakelas.php">Data Kelas</a></li>
					<li class="nav-item"><a class="nav-link" href="datapetugas.php">Data Petugas</a></li>
					<li class="nav-item"><a class="nav-link" href="datapembayaran.php">Pembayaran</a></li>
					<li class="nav-item"><a class="nav-link" href="cekpembayaran.php">Cek Pembayaran</a></li>
					<li class="nav-item"><a class="nav-link active" href="detaildatapembayaran.php" aria-current="page">Detail Pembayaran</a></li>
				</ul>
			</nav>
		</aside>

		<main class="flex-grow-1 p-4 p-lg-5">
			<header class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
				<div>
					<h1 class="h3 mb-1">Detail Pembayaran</h1>
					<p class="text-secondary mb-0">Rincian seluruh transaksi pembayaran</p>
				</div>
				<a href="datapembayaran.php" class="btn btn-outline-secondary">Kembali ke pembayaran</a>
			</header>

			<section class="bg-white border rounded-2 p-3 p-lg-4" aria-label="Daftar detail pembayaran">
				<?php if ($queryError): ?>
					<div class="alert alert-warning mb-0" role="alert">
						Data tidak dapat ditampilkan. Pastikan tabel <code>tb_pembayaran</code> tersedia di database.
					</div>
				<?php elseif (mysqli_num_rows($result) === 0): ?>
					<p class="text-secondary mb-0">Belum ada data pembayaran.</p>
				<?php else: ?>
					<div class="table-responsive">
						<table class="table table-striped table-hover align-middle mb-0">
							<thead>
								<tr>
									<?php foreach ($columns as $column): ?>
										<th scope="col"><?= htmlspecialchars($column->name, ENT_QUOTES, 'UTF-8') ?></th>
									<?php endforeach; ?>
								</tr>
							</thead>
							<tbody>
								<?php while ($payment = mysqli_fetch_assoc($result)): ?>
									<tr>
										<?php foreach ($columns as $column): ?>
											<?php
											$value = (string) ($payment[$column->name] ?? '');
											if (strtolower($column->name) === 'tgl_terakhir_bayar' && $value === '0000-00-00') {
												$value = '';
											}
											?>
											<td><?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?></td>
										<?php endforeach; ?>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>
			</section>
		</main>
	</div>
</body>
</html>
<?php
if ($result !== false) {
	 mysqli_free_result($result);
}
mysqli_close($conn);
?>
