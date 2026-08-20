# Task 3 — Analisa & Design Kebutuhan Data

## 1. Analisa Kebutuhan Data (dari studi kasus)

| Kebutuhan fungsional | Data yang dibutuhkan |
|----------------------|----------------------|
| Login 2 peran (pengurus & warga) | `users` (nama, email, password, role) |
| Pencatatan warga & status aktif | `warga` (nama, no_rumah, alamat, no_hp) |
| Iuran bulanan yang berjalan & riwayat perubahan nominal | `pengaturan_iuran` (nominal, berlaku_mulai) |
| Warga membayar iuran per periode bulan | `pembayaran` (warga, periode, nominal, tanggal) |
| Tagihan otomatis di awal bulan | dihitung dari `pengaturan_iuran` terbaru |
| Info siapa sudah/belum bayar & pembayaran macet | query pada `pembayaran` vs `warga` |
| Pengeluaran kategori Kas, Sosial, Konsumsi | `kategori_pengeluaran` + `pengeluaran` |
| Laporan bulanan | agregasi `pembayaran` & `pengeluaran` |

## 2. Entity Relationship Diagram (ERD)

```
┌────────────┐ 1       0..1 ┌─────────┐ 1      0..* ┌──────────────┐
│   users    │─────────────│  warga  │──────────────│  pembayaran  │
│  (login)   │             └─────────┘              └──────┬───────┘
└────────────┘               │                            │
                             │ 0..*                       │ n
                             ▼                            ▼
                     ┌──────────────┐        ┌──────────────┐
                     │pengaturan_iuran│ 0..*  │   iuran (FK) │
                     └──────────────┘       └──────────────┘

┌────────────┐ 1       0..* ┌─────────────┐
│kategori_   │──────────────│ pengeluaran │
│pengeluaran │              └─────────────┘
└────────────┘
```

Relasi:
- `users` 1—1 `warga` (warga bisa login), `users` 1—0..* pembayaran via warga.
- `warga` 1—0..* `pembayaran` (setiap bulan boleh bayar, unik per periode).
- `pengaturan_iuran` 1—0..* `pembayaran` (pembayaran merekam nominal yang berlaku).
- `kategori_pengeluaran` 1—0..* `pengeluaran`.

## 3. Rincian Tabel

### 3.1 `users` — akun login
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT PK AI | |
| nama | VARCHAR(100) | |
| email | VARCHAR(100) UNIQUE | |
| password | VARCHAR(255) | hash |
| role | ENUM('pengurus','warga') | |
| aktif | TINYINT | |

### 3.2 `warga` — data warga
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT PK AI | |
| user_id | INT FK→users | null jika belum punya akun |
| nama | VARCHAR(100) | |
| no_rumah | VARCHAR(10) UNIQUE | |
| alamat | VARCHAR(255) | |
| no_hp | VARCHAR(20) | |
| status | ENUM('aktif','nonaktif') | |

### 3.3 `pengaturan_iuran` — nominal iuran (historis)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT PK AI | |
| nominal | DECIMAL(12,2) | |
| berlaku_mulai | DATE | iuran berjalan = record terbaru |

### 3.4 `pembayaran` — pembayaran iuran
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT PK AI | |
| warga_id | INT FK→warga | |
| iuran_id | INT FK→pengaturan_iuran | nominal yang berlaku saat bayar |
| periode | VARCHAR(7) | `'2026-08'` |
| nominal | DECIMAL(12,2) | |
| tanggal_bayar | DATE | |
| metode | ENUM('tunai','transfer') | |
| bukti | VARCHAR(255) | path file bukti |
| catatan | VARCHAR(255) | |
| status | ENUM('lunas','tertunda') | |
| **UNIQUE (warga_id, periode)** | | mencegah dobel bayar 1 periode |

### 3.5 `kategori_pengeluaran` — Kas, Sosial, Konsumsi
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT PK AI | |
| nama | VARCHAR(50) UNIQUE | |
| keterangan | VARCHAR(255) | |

### 3.6 `pengeluaran` — pengeluaran RT
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT PK AI | |
| kategori_id | INT FK→kategori_pengeluaran | |
| tanggal | DATE | |
| jumlah | DECIMAL(12,2) | |
| keterangan | VARCHAR(255) | |
| foto_bukti | VARCHAR(255) | |

## 4. Aturan Bisnis yang Diakomodasi

1. **Tagihan di awal bulan** → generate dari `warga` (status aktif) x
   `pengaturan_iuran` terbaru.
2. **Satu pembayaran per warga per periode** → constraint `UNIQUE(warga_id, periode)`.
3. **Pembayaran macet** → warga aktif yang tidak memiliki `pembayaran` untuk
   periode berjalan (lihat query `NOT EXISTS` pada file SQL).
4. **Laporan bulanan** → `SUM(pembayaran.nominal)` sebagai pemasukan,
   `SUM(pengeluaran.jumlah)` per kategori sebagai pengeluaran.
5. **Saldo kas** = total pembayaran lunas − total pengeluaran.
6. **Perubahan nominal iuran** → dicatat sebagai record baru di
   `pengaturan_iuran` tanpa mengubah riwayat pembayaran lama.

## 5. Implementasi

- File SQL lengkap + seed + query laporan: `database/aplikasi_rt.sql`
- Diimpor melalui phpMyAdmin / MySQL Workbench / CLI:
  ```bash
  mysql -u root -p < database/aplikasi_rt.sql
  ```
