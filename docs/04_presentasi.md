# Naskah Presentasi — Aplikasi Iuran RT

Durasi ±10 menit, 13 slide (buka `presentasi/index.html`).

## Slide 1 — Judul
- Sapaan, perkenalan nama & NIM.
- Judul: "Aplikasi Iuran RT — Pencatatan Pemasukan & Pengeluaran Kas RT".

## Slide 2 — Latar Belakang & Tujuan
- Pencatatan manual di buku rawan salah dan sulit dipantau.
- Tujuan: digitalisasi iuran, monitoring pembayaran, laporan bulanan otomatis.

## Slide 3 — Deskripsi Kebutuhan
- Dua peran: pengurus (kelola, pantau, lapor) dan warga (bayar, riwayat).
- Pemasukan = iuran; pengeluaran = Kas, Sosial, Konsumsi.

## Slide 4 — Task 1: Pemilihan Template
- Pilih **AdminLTE 3 (v3.2.0)**: Bootstrap 4, MIT, gratis, struktur layout
  jelas, banyak komponen siap pakai.
- Alternatif yang dipertimbangkan: Stisla, SB Admin 2, Argon.

## Slide 5–7 — Task 2: Slicing Layout
- Jelaskan konsep `extend()` / `section()` di CodeIgniter 4.
- Tunjukkan folder `components` (header, navbar, sidebar, footer) + file
  `layout.php`.
- Tunjukkan contoh halaman dan peta komponen (slide 7: tabel halaman + URL).
- **Demo live**: jalankan `php spark serve`, tampilkan beberapa halaman.

## Slide 8–10 — Task 3: Design Database
- 6 tabel, fungsi tiap tabel.
- ERD & relasi; penekanan: `UNIQUE(warga_id, periode)` anti dobel bayar,
  nominal iuran historis.
- Query pembayaran macet & saldo kas.

## Slide 11 — Demo & Rencana Pengembangan
- Demo singkat halaman.
- Rencana: autentikasi login per peran, CRUD terhubung database, cetak PDF,
  notifikasi tagihan.

## Slide 12 — Terima Kasih
- Undang pertanyaan.

## Catatan Demo
- Server: `php spark serve` lalu buka `http://localhost:8080`.
- Halaman pengurus: `/`, `/pembayaran`, `/laporan`.
- Halaman warga: `/warga/tagihan`, `/warga/history`.
