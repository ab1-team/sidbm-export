# sidbm-export

Aplikasi Laravel untuk mengarsipkan data historis SIDBM (saldo & transaksi) ke EnStorage.

## Cara Kerja

1. Membaca data dari database SIDBM utama (`saldo_{id}`, `transaksi_{id}`)
2. Mentransformasi data ke format JSON yang efisien
3. Mengupload ke EnStorage dengan struktur folder per kecamatan
4. Menyimpan metadata export di database lokal

## Struktur File JSON

**Saldo** — 1 file per kecamatan per tahun, format wide:
```
kecamatan_1/saldo_2023.json
```

**Transaksi** — 1 file per bulan per tahun per kecamatan:
```
kecamatan_1/transaksi_2023_01.json
kecamatan_1/transaksi_2023_02.json
...
kecamatan_1/transaksi_2023_12.json
```

## Setup

```bash
# 1. Clone & install dependencies
git clone https://github.com/[username]/sidbm-export.git
cd sidbm-export
composer install

# 2. Konfigurasi
cp .env.example .env
php artisan key:generate

# Edit .env: isi DB_*, SIDBM_DB_*, dan ENSTORAGE_*

# 3. Buat database lokal & jalankan migrasi
php artisan migrate

# 4. Jalankan server
php artisan serve
```

## Artisan Commands

```bash
# Export satu kecamatan, satu tahun
php artisan export:arsip --kecamatan=1 --tahun=2023

# Export saldo saja
php artisan export:arsip --kecamatan=1 --tahun=2023 --jenis=saldo

# Export transaksi saja
php artisan export:arsip --kecamatan=1 --tahun=2023 --jenis=transaksi

# Export semua kecamatan, satu tahun
php artisan export:arsip --all --tahun=2023

# Export semua kecamatan, semua tahun yang memenuhi batas arsip
php artisan export:arsip --all
```

## Struktur Project

```
app/
├── Console/Commands/
│   └── ExportArsip.php         ← Artisan command
├── Http/Controllers/
│   └── ExportController.php    ← Controller UI
├── Models/
│   ├── ExportLog.php           ← Model metadata export
│   └── Sidbm/
│       ├── Kecamatan.php       ← Model kecamatan (dari DB SIDBM)
│       ├── SaldoModel.php      ← Model dinamis saldo_{id}
│       └── TransaksiModel.php  ← Model dinamis transaksi_{id}
└── Services/
    ├── EnStorageService.php      ← Integrasi API EnStorage
    ├── SaldoExportService.php    ← Logika export saldo
    └── TransaksiExportService.php← Logika export transaksi
```
