# Task 1 — Analisa & Pemilihan Template Web

## 1. Kebutuhan Template

Aplikasi Iuran RT adalah aplikasi **admin/dashboard** dengan 2 peran pengguna
(pengurus dan warga). Maka template yang dibutuhkan:

- **Admin dashboard / back-office** (bukan landing page)
- Menyediakan layout lengkap: **header, navbar, sidebar, footer**
- Responsif (mendukung akses dari HP warga)
- Gratis & mudah di-slice ke CodeIgniter 4
- Tema Bootstrap agar mudah dimodifikasi

## 2. Alternatif Template yang Dipertimbangkan

| Template | Framework CSS | Lisensi | Keunggulan |
|----------|---------------|---------|------------|
| **AdminLTE 3** | Bootstrap 4 | MIT | Paling populer, dokumentasi lengkap, struktur jelas, gratis |
| Stisla | Bootstrap 4 | MIT | Ringan, populer di komunitas Indonesia |
| SB Admin 2 | Bootstrap 4 | MIT | Sederhana, cocok untuk proyek kuliah |
| Argon Dashboard | Bootstrap 4 | MIT | Tampilan modern |

## 3. Template Terpilih: **AdminLTE 3 (v3.2.0)**

Alasan pemilihan:

1. **Struktur layout sangat jelas** — ada `wrapper`, `main-header` (navbar),
   `main-sidebar`, `content-wrapper`, dan `main-footer`, sehingga mudah di-slice
   menjadi komponen header, navbar, sidebar, dan footer.
2. **Lisensi MIT** — bebas digunakan untuk tugas kuliah.
3. **Berbasis Bootstrap 4** — kompatibel dengan banyak plugin (datatable, chart).
4. **Komponen siap pakai** — *info box*, *small box*, tabel, form, dsb. yang
   sesuai untuk halaman monitoring iuran dan laporan.
5. **Dokumentasi & komunitas besar** — mudah mencari contoh implementasi.

## 4. Sumber & Struktur Template

- Nama : AdminLTE 3.2.0
- Sumber : https://github.com/ColorlibHQ/AdminLTE/releases/tag/v3.2.0
- Lokasi unduhan pada proyek ini : `template_adminlte/`

Struktur penting template:

```
template_adminlte/
├── dist/                     # hasil build
│   ├── css/adminlte.min.css  # style utama
│   ├── js/adminlte.min.js    # script utama (pushmenu, dsb)
│   └── img/                  # logo & avatar contoh
├── plugins/                  # plugin pendukung
│   ├── bootstrap/            # Bootstrap 4 bundle
│   ├── jquery/               # jQuery
│   └── fontawesome-free/     # ikon Font Awesome
├── pages/                    # contoh halaman template
├── starter.html              # halaman awal paling sederhana (dasar slicing)
└── index.html                # halaman demo
```

> Aset yang dibutuhkan layout disalin ke `aplikasi-rt/public/assets/`
> (prinsip *slicing*: hanya ambil bagian yang dipakai).
