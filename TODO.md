# TODO: Integrasi Google Sheets untuk Input Surat

## ✅ Selesai
- [x] Buat composer.json
- [x] Buat google_sheets.php

## ⏳ Langkah Selanjutnya
1. Install dependencies:
   ```
   cd c:/xampp/htdocs/WEBADMINSYAALLAHFIXX
   composer install
   ```
2. Setup Google:
   - Buat project Google Cloud Console → Enable Sheets API
   - Create Service Account → Download service_account.json ke root project
   - Buat 2 Sheet: "Surat Masuk" & "Surat Keluar"
   - Tambah header row A1: Timestamp|Nomor|Nomor Surat|Perihal|Nama Pengirim/Penerima|Instansi|Tanggal|Nama File (sesuaikan)
   - Share sheet dengan email service account (dari JSON)
   - Edit google_sheets.php: Ganti SHEET_ID_MASUK & SHEET_ID_KELUAR dengan ID sheet kamu
3. Update forms (akan dibuat selanjutnya setelah konfirmasi)
4. Test:
   - Start XAMPP
   - Buka form_suratmasuk.php
   - Input → Submit → Cek DB & Sheet

## Notes
- Sheets append **tambah** ke DB, tidak ganti.
- Kalau error, cek error_log Apache.

Lanjut step berikutnya?

