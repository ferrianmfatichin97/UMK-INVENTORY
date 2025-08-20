<p align="center"><a href="https://bankdptaspen.co.id/" target="_blank"><img src="https://bankdptaspen.co.id/wp-content/uploads/2024/01/Logo-Bank-DP-Taspen-Version-New.png" width="400" alt="Bank BPR DP Taspen Logo"></a></p>

# 📌 Aplikasi UMK & Inventaris

Aplikasi berbasis web untuk **pengelolaan UMK (Uang Muka Kerja)** dan **Inventaris Kantor**.  
Dibangun menggunakan **Laravel + FilamentPHP**, aplikasi ini memudahkan pengelolaan proses pengajuan, transaksi, laporan UMK, serta pencatatan inventaris perusahaan.

---

## 🚀 Fitur Utama

### Modul UMK
- Pengajuan UMK
- Persetujuan UMK
- Pencatatan transaksi UMK
- Laporan:
  - Laporan Pertanggungjawaban (LPJ)
  - Laporan Transaksi UMK
  - Laporan Rekap Transaksi
  - Laporan Selisih Pengajuan vs Realisasi

### Modul Inventaris
- Input & manajemen data inventaris
- Kategori & lokasi inventaris
- Laporan inventaris

### Fitur Pendukung
- Autentikasi & manajemen user (Laravel Breeze/Jetstream/Filament bawaan)
- Export PDF laporan (dengan DomPDF)
- Cron Job & Scheduler untuk task otomatis
- Tampilan berbasis **Filament Admin Panel**

---

## 🛠️ Teknologi

- [Laravel 10](https://laravel.com/)
- [FilamentPHP](https://filamentphp.com/)
- [MySQL/MariaDB](https://www.mysql.com/)
- [DomPDF](https://github.com/barryvdh/laravel-dompdf) → untuk export laporan PDF
- [Carbon](https://carbon.nesbot.com/) → manipulasi tanggal
- [Riskihajar/Terbilang](https://github.com/riskihajar/terbilang) → konversi angka ke teks Rupiah

---

## ⚙️ Instalasi

1. Clone repository:
   ```bash
   git clone https://github.com/username/umk-inventaris.git
   cd umk-inventaris
