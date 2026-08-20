<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'Home::index', ['filter' => 'auth']);

// Auth
$routes->get('login', 'AuthController::login');
$routes->post('login/proses', 'AuthController::attemptLogin');
$routes->post('logout', 'AuthController::logout');

// Pengurus
$routes->get('warga', 'DataWarga::index', ['filter' => 'auth:pengurus']);
$routes->post('warga/tambah', 'DataWarga::tambah', ['filter' => 'auth:pengurus']);
$routes->get('warga/edit/(:num)', 'DataWarga::edit/$1', ['filter' => 'auth:pengurus']);
$routes->post('warga/update/(:num)', 'DataWarga::update/$1', ['filter' => 'auth:pengurus']);
$routes->post('warga/hapus/(:num)', 'DataWarga::hapus/$1', ['filter' => 'auth:pengurus']);

$routes->get('users', 'UserManagement::index', ['filter' => 'auth:pengurus']);
$routes->post('users/tambah', 'UserManagement::tambah', ['filter' => 'auth:pengurus']);
$routes->get('users/edit/(:num)', 'UserManagement::edit/$1', ['filter' => 'auth:pengurus']);
$routes->post('users/update/(:num)', 'UserManagement::update/$1', ['filter' => 'auth:pengurus']);
$routes->post('users/hapus/(:num)', 'UserManagement::hapus/$1', ['filter' => 'auth:pengurus']);

$routes->get('iuran', 'Iuran::index', ['filter' => 'auth:pengurus']);
$routes->post('iuran/tambah', 'Iuran::tambah', ['filter' => 'auth:pengurus']);

$routes->get('pembayaran', 'Pembayaran::index', ['filter' => 'auth:pengurus']);
$routes->post('pembayaran/tambah', 'Pembayaran::tambah', ['filter' => 'auth:pengurus']);
$routes->post('pembayaran/hapus/(:num)', 'Pembayaran::hapus/$1', ['filter' => 'auth:pengurus']);
$routes->post('pembayaran/setuju/(:num)', 'Pembayaran::setuju/$1', ['filter' => 'auth:pengurus']);
$routes->post('pembayaran/tolak/(:num)', 'Pembayaran::tolak/$1', ['filter' => 'auth:pengurus']);
$routes->get('pembayaran/riwayat/(:num)', 'Pembayaran::riwayat/$1', ['filter' => 'auth:pengurus']);
$routes->get('pembayaran/macet', 'Macet::index', ['filter' => 'auth:pengurus']);

$routes->get('pengeluaran', 'Pengeluaran::index', ['filter' => 'auth:pengurus']);
$routes->post('pengeluaran/tambah', 'Pengeluaran::tambah', ['filter' => 'auth:pengurus']);
$routes->get('pengeluaran/edit/(:num)', 'Pengeluaran::edit/$1', ['filter' => 'auth:pengurus']);
$routes->post('pengeluaran/update/(:num)', 'Pengeluaran::update/$1', ['filter' => 'auth:pengurus']);
$routes->post('pengeluaran/hapus_foto/(:num)', 'Pengeluaran::hapus_foto/$1', ['filter' => 'auth:pengurus']);
$routes->post('pengeluaran/hapus/(:num)', 'Pengeluaran::hapus/$1', ['filter' => 'auth:pengurus']);

$routes->get('kategori-pengeluaran', 'KategoriPengeluaran::index', ['filter' => 'auth:pengurus']);
$routes->post('kategori-pengeluaran/tambah', 'KategoriPengeluaran::tambah', ['filter' => 'auth:pengurus']);
$routes->get('kategori-pengeluaran/edit/(:num)', 'KategoriPengeluaran::edit/$1', ['filter' => 'auth:pengurus']);
$routes->post('kategori-pengeluaran/update/(:num)', 'KategoriPengeluaran::update/$1', ['filter' => 'auth:pengurus']);
$routes->post('kategori-pengeluaran/hapus/(:num)', 'KategoriPengeluaran::hapus/$1', ['filter' => 'auth:pengurus']);

$routes->get('laporan', 'Laporan::index', ['filter' => 'auth:pengurus']);

// Export (pengurus)
$routes->get('laporan/cetak', 'Export::laporanPDF', ['filter' => 'auth:pengurus']);
$routes->get('laporan/export', 'Export::laporanExcel', ['filter' => 'auth:pengurus']);
$routes->get('warga/export', 'Export::dataWargaExcel', ['filter' => 'auth:pengurus']);
$routes->get('pembayaran/export', 'Export::pembayaranExcel', ['filter' => 'auth:pengurus']);

// Warga
$routes->get('warga/tagihan', 'Warga::tagihan', ['filter' => 'auth:warga']);
$routes->get('warga/history', 'Warga::history', ['filter' => 'auth:warga']);
$routes->get('warga/laporan', 'Warga::laporan', ['filter' => 'auth:warga']);
$routes->post('warga/bayar', 'Warga::bayar', ['filter' => 'auth:warga']);

// Export (warga)
$routes->get('warga/cetak-bukti/(:num)', 'Export::strukBayar/$1', ['filter' => 'auth:warga']);
$routes->get('warga/export-history', 'Export::riwayatExcel', ['filter' => 'auth:warga']);
