# Task 2 — Slicing Template ke dalam Komponen Layout (CodeIgniter 4)

## 1. Konsep Layout di CodeIgniter 4

CodeIgniter 4 mendukung **layout** melalui `$this->extend()` dan `$this->section()`.
Halaman hanya berisi kontennya saja, sedangkan kerangka halaman (HTML, CSS, menu,
navbar, footer) diletakkan sekali pada file Layout dan komponennya.

## 2. Struktur Folder `app/Views`

```
app/Views/
├── layout/
│   └── layout.php            # FILE LAYOUT utama
├── components/               # FOLDER KOMPONEN
│   ├── header.php            # <head>, CSS, pembuka body & wrapper
│   ├── navbar.php            # top bar / menu atas
│   ├── sidebar.php           # sidebar kiri (menu pengurus & warga)
│   └── footer.php            # footer + script penutup
└── pages/
    ├── dashboard.php         # beranda pengurus (extend layout)
    ├── pembayaran.php        # monitoring pembayaran iuran
    ├── laporan.php           # laporan kas bulanan
    ├── warga_tagihan.php     # tagihan iuran (peran warga)
    └── warga_history.php     # riwayat pembayaran (peran warga)
```

## 3. Alur Slicing (dari `starter.html` template)

1. **Header** → bagian `<head>` + pembuka `<body>` + `<div class="wrapper">`
   (CSS: Google Font, Font Awesome, `adminlte.min.css`).
2. **Navbar** → `<nav class="main-header navbar ...">` (tombol menu, beranda,
   profil, keluar).
3. **Sidebar** → `<aside class="main-sidebar ...">` (logo, panel pengguna, menu
   navigasi). Menu dibedakan peran: **pengurus** (Kelola Warga, Pengaturan Iuran,
   Monitoring Pembayaran, Pembayaran Macet, Pengeluaran, Laporan) dan **warga**
   (Tagihan Saya, Riwayat Pembayaran).
4. **Footer** → `<footer class="main-footer">` + control sidebar + script
   jQuery, Bootstrap, dan `adminlte.min.js`.
5. **File Layout** → merangkai keempat komponen + menyediakan tempat isi konten:
   `<?= $this->renderSection('content') ?>`.

## 4. Cara Kerja File Layout

`layout/layout.php` menerima variabel dari controller:
- `$title` / `$page_title` — judul halaman & breadcrumb
- `$active` — menu yang sedang aktif (highlight)
- `$role` — peran pengguna (`pengurus` / `warga`)
- `$nama` — nama pengguna yang login

Contoh sebuah halaman (misal `pages/dashboard.php`):

```php
<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>
<!-- isi halaman: info box, tabel, dsb -->
<?= $this->endSection() ?>
```

## 5. Peta komponen → bagian template AdminLTE

| Komponen hasil slicing | Bagian pada template AdminLTE |
|------------------------|-------------------------------|
| `components/header.php` | `<head>`, CSS, `body`, `.wrapper` |
| `components/navbar.php` | `.main-header.navbar` |
| `components/sidebar.php` | `.main-sidebar` (brand + menu) |
| `components/footer.php` | `.main-footer`, `.control-sidebar`, script |
| `layout/layout.php` | `.content-wrapper` (judul + `renderSection('content')`) |

## 6. Controller & Route

- `Home` (dashboard pengurus), `Pembayaran`, `Laporan`, `Warga` (tagihan + riwayat)
  di `app/Controllers/`, masing-masing mengirim data `title, active, role, nama`.
- Route didaftarkan di `app/Config/Routes.php`:

```php
$routes->get('/',                'Home::index');
$routes->get('pembayaran',       'Pembayaran::index');
$routes->get('laporan',          'Laporan::index');
$routes->get('warga/tagihan',    'Warga::tagihan');
$routes->get('warga/history',    'Warga::history');
```

## 7. Aset Template

Aset yang diperlukan disalin dari `template_adminlte/` ke `public/assets/`:

```
public/assets/
├── css/adminlte.min.css
├── js/adminlte.min.js
├── img/                                # logo & avatar
└── plugins/
    ├── jquery/jquery.min.js
    ├── bootstrap/js/bootstrap.bundle.min.js
    └── fontawesome-free/               # css + webfonts
```

## 8. Menjalankan Aplikasi

```bash
cd aplikasi-rt
php spark serve            # default: http://localhost:8080
```

| URL | Halaman | Peran |
|-----|---------|-------|
| `/` | Dashboard pengurus | pengurus |
| `/pembayaran` | Monitoring pembayaran | pengurus |
| `/laporan` | Laporan kas bulanan | pengurus |
| `/warga/tagihan` | Tagihan iuran | warga |
| `/warga/history` | Riwayat pembayaran | warga |
