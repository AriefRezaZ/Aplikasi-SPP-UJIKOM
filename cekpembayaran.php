<?php
require_once 'koneksi.php';

$result = mysqli_query($conn, 'SELECT * FROM cek_pembayaran');
$queryError = $result === false;
$columns = $queryError ? [] : mysqli_fetch_fields($result);
$rows = [];
$statusColumn = null;
$paidRows = [];
$unpaidRows = [];
$unknownRows = [];

if (!$queryError) {
	$statusColumnNames = ['status', 'status_pembayaran', 'status_bayar', 'payment_status', 'keterangan', 'lunas'];
	foreach ($columns as $column) {
		if (in_array(strtolower($column->name), $statusColumnNames, true)) {
			$statusColumn = $column->name;
			break;
		}
	}

	while ($row = mysqli_fetch_assoc($result)) {
		$rows[] = $row;
		if ($statusColumn === null) {
			continue;
		}

		$status = strtolower(trim((string) ($row[$statusColumn] ?? '')));
		
		if (in_array($status, ['Belum Lunas', 'belum lunas'], true)) {
			$unpaidRows[] = $row;
		} elseif (in_array($status, ['Sudah Lunas', 'sudah lunas'], true)) {
			$paidRows[] = $row;
		} else {
			$unknownRows[] = $row;
		}
	}
}

function renderPaymentTable(array $rows, array $columns): void
{
	if (count($rows) === 0) {
		echo '<p class="text-secondary mb-0">Tidak ada data.</p>';
		return;
	}
	?>
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
				<?php foreach ($rows as $row): ?>
					<tr>
						<?php foreach ($columns as $column): ?>
							<?php
							$value = (string) ($row[$column->name] ?? '');
							if (strtolower($column->name) === 'tgl_terakhir_bayar' && $value === '0000-00-00') {
								$value = '';
							}
							?>
							<td><?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?></td>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cek Pembayaran - Aplikasi SPP</title>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<style>
		body {
			min-height: 100vh;
			background: #f4f6f5;
		}

		.sidebar {
			width: 250px;
			min-height: 100vh;
			background: #173b35;
		}

		.sidebar .nav-link {
			color: #d7e4df;
			border-radius: 6px;
			padding: 10px 12px;
		}

		.sidebar .nav-link:hover,
		.sidebar .nav-link.active {
			color: #fff;
			background: #28574e;
		}

		@media (max-width: 767.98px) {
			.sidebar {
				width: 100%;
				min-height: auto;
			}

			.sidebar .nav {
				flex-direction: row !important;
				flex-wrap: wrap;
			}
		}
	</style>
</head>
<body>
	<div class="d-md-flex min-vh-100">
		<aside class="sidebar p-3">
			<a href="dashboard_admin.php" class="d-block text-white text-decoration-none fs-5 fw-semibold mb-4 px-2">
				Aplikasi SPP
			</a>
			<nav aria-label="Navigasi utama">
				<ul class="nav nav-pills flex-column gap-1">
					<li class="nav-item"><a class="nav-link" href="dashboard_admin.php">Dashboard</a></li>
					<li class="nav-item"><a class="nav-link" href="datasiswa.php">Data Siswa</a></li>
					<li class="nav-item"><a class="nav-link" href="datakelas.php">Data Kelas</a></li>
					<li class="nav-item"><a class="nav-link" href="datapetugas.php">Data Petugas</a></li>
					<li class="nav-item"><a class="nav-link" href="datapembayaran.php">Pembayaran</a></li>
					<li class="nav-item"><a class="nav-link active" href="cekpembayaran.php" aria-current="page">Cek Pembayaran</a></li>
					<li class="nav-item"><a class="nav-link" href="detaildatapembayaran.php">Detail Pembayaran</a></li>
				</ul>
			</nav>
		</aside>

		<main class="flex-grow-1 p-4 p-lg-5">
			<header class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
				<div>
					<h1 class="h3 mb-1">Cek Pembayaran</h1>
					<p class="text-secondary mb-0">Status pembayaran siswa</p>
				</div>
				<a href="dashboard_admin.php" class="btn btn-outline-secondary">Kembali ke dashboard</a>
			</header>

			<?php if ($queryError): ?>
				<div class="alert alert-warning" role="alert">
					Data tidak dapat ditampilkan. Pastikan tabel <code>cek_pembayaran</code> tersedia di database.
				</div>
			<?php elseif ($statusColumn === null): ?>
				<div class="alert alert-warning" role="alert">
					Tidak ditemukan kolom status. Gunakan kolom <code>status</code>, <code>status_pembayaran</code>, atau <code>status_bayar</code> untuk memisahkan pembayaran.
				</div>
			<?php else: ?>
				<?php if (count($unknownRows) > 0): ?>
					<div class="alert alert-info" role="status">
						<?= count($unknownRows) ?> data memiliki nilai status yang tidak dikenali pada kolom <code><?= htmlspecialchars($statusColumn, ENT_QUOTES, 'UTF-8') ?></code> dan tidak dimasukkan ke kedua daftar.
					</div>
				<?php endif; ?>

				<section class="bg-white border rounded-2 p-3 p-lg-4 mb-4" aria-labelledby="paid-title">
					<h2 id="paid-title" class="h5 mb-3">Sudah Lunas <span class="badge text-bg-success"><?= count($paidRows) ?></span></h2>
					<?php renderPaymentTable($paidRows, $columns); ?>
				</section>

				<section class="bg-white border rounded-2 p-3 p-lg-4" aria-labelledby="unpaid-title">
					<h2 id="unpaid-title" class="h5 mb-3">Belum Lunas <span class="badge text-bg-warning"><?= count($unpaidRows) ?></span></h2>
					<?php renderPaymentTable($unpaidRows, $columns); ?>
				</section>
			<?php endif; ?>
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
