# Aplikasi Iuran RT — Paket Tugas Lengkap (Task 1–4)

Mata Kuliah **Pemrograman Web** — Studi Kasus: Aplikasi RT (pencatatan iuran,
kas, dan kegiatan sosial).

## Isi Paket

```
Aplikasi_RT/
├── template_adminlte/     # [Task 1] Template AdminLTE 3.2.0 (hasil unduhan)
├── aplikasi-rt/           # [Task 2] Proyek CodeIgniter 4 hasil slicing layout
│   └── app/Views/
│       ├── layout/layout.php      # File Layout
│       ├── components/            # header, navbar, sidebar, footer
│       └── pages/                 # dashboard, pembayaran, laporan, tagihan, history
├── database/
│   └── aplikasi_rt.sql            # [Task 3] Skema + seed + query laporan
├── docs/
│   ├── 01_template.md             # Analisa & pemilihan template
│   ├── 02_slicing_layout.md       # Penjelasan slicing layout
│   ├── 03_design_database.md      # Analisa & design kebutuhan data
│   └── 04_presentasi.md           # Naskah presentasi
├── presentasi/
│   ├── index.html                 # [Task 4] Slide presentasi (browser)
│   └── Presentasi_Aplikasi_RT.pptx # [Task 4] Slide PowerPoint (12 slide)
└── Laporan_Aplikasi_RT.docx       # Laporan gabungan siap kumpul
```

## Cara Menjalankan Aplikasi (Task 2)

```bash
cd aplikasi-rt
php spark serve        # buka http://localhost:8080
```

| URL | Halaman | Peran |
|-----|---------|-------|
| `/` | Dashboard pengurus | pengurus |
| `/pembayaran` | Monitoring pembayaran | pengurus |
| `/laporan` | Laporan kas bulanan | pengurus |
| `/warga/tagihan` | Tagihan iuran | warga |
| `/warga/history` | Riwayat pembayaran | warga |

## Cara Import Database (Task 3)

MySQL/MariaDB sudah terpasang:

```bash
mysql -u root -p < database/aplikasi_rt.sql
```

atau import `database/aplikasi_rt.sql` lewat phpMyAdmin.

## Presentasi (Task 4)

Dua pilihan:

- **PowerPoint**: buka `presentasi/Presentasi_Aplikasi_RT.pptx` (12 slide, bisa
  diedit langsung di PowerPoint).
- **Browser**: buka `presentasi/index.html` (tombol panah kanan/kiri atau klik
  untuk berpindah slide).

Naskah/catatan penyaji ada di `docs/04_presentasi.md`.

## Ringkasan Jawaban Tiap Tugas

1. **Template**: AdminLTE 3.2.0 — dashboard Bootstrap 4, lisensi MIT, struktur
   layout lengkap (navbar, sidebar, content, footer).
2. **Slicing**: `starter.html` dipecah ke `app/Views/components/` (header,
   navbar, sidebar, footer) dan dirangkai `app/Views/layout/layout.php` dengan
   `renderSection('content')`.
3. **Database**: 6 tabel (users, warga, pengaturan_iuran, pembayaran,
   kategori_pengeluaran, pengeluaran) + constraint anti-dobel-bayar + query
   laporan.
4. **Presentasi**: 13 slide HTML (latar belakang, template, slicing, database,
   demo, rencana pengembangan).
