	<?php
	session_start();
	$nama = $_SESSION['role'] ?? 'administrator';
	?>
	<!DOCTYPE html>
	<html lang="id">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Dashboard Admin - Aplikasi SPP</title>
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
				<a href="dashboard.php" class="d-block text-white text-decoration-none fs-5 fw-semibold mb-4 px-2">
					Aplikasi SPP
				</a>
				<nav aria-label="Navigasi utama">
					<ul class="nav nav-pills flex-column gap-1">
						<li class="nav-item"><a class="nav-link active" href="dashboard_admin.php" aria-current="page">Dashboard</a></li>
						<li class="nav-item"><a class="nav-link" href="datasiswa.php">Data Siswa</a></li>
						<li class="nav-item"><a class="nav-link" href="datakelas.php">Data Kelas</a></li>
						<li class="nav-item"><a class="nav-link" href="datapetugas.php">Data Petugas</a></li>
						<li class="nav-item"><a class="nav-link" href="datapembayaran.php">Pembayaran</a></li>
						<li class="nav-item"><a class="nav-link" href="cekpembayaran.php">Cek Pembayaran</a></li>
						<li class="nav-item"><a class="nav-link" href="detaildatapembayaran.php">Detail Pembayaran</a></li>
					</ul>
				</nav>
			</aside>

			<main class="flex-grow-1 p-4 p-lg-5">
				<header class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
					<div>
						<h1 class="h3 mb-1">Dashboard</h1>
						<p class="text-secondary mb-0">Aplikasi Pembayaran SPP</p>
					</div>
					<span class="text-secondary">Halo, <?= htmlspecialchars($nama, ENT_QUOTES, 'UTF-8') ?></span>
				</header>

				<section class="bg-white border rounded-2 p-4 p-lg-5" aria-labelledby="welcome-title">
					<h2 id="welcome-title" class="h4">Selamat datang</h2>
					<p class="text-secondary mb-0">Gunakan menu di sebelah kiri untuk mengelola data siswa, kelas, petugas, dan pembayaran SPP.</p>
				</section>
			</main>
		</div>
	</body>
	</html>
    