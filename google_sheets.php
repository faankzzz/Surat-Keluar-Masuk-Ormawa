<?php
/**
 * Google Sheets Integration
 * 1. Buat project di https://console.cloud.google.com
 * 2. Enable Google Sheets API
 * 3. Credentials > Create Credentials > Service Account
 * 4. Download JSON sebagai service_account.json di folder ini
 * 5. Share Sheet dengan email service account (di JSON)
 * 6. Ganti SHEET_ID_MASUK dan SHEET_ID_KELUAR
 */

require_once 'vendor/autoload.php';

function appendToGoogleSheet($sheetId, $rowData) {
    try {
        $client = new Google_Client();
        $client->setAuthConfig('service_account.json');
        $client->addScope(Google_Service_Sheets::SPREADSHEETS);

        $service = new Google_Service_Sheets($client);

        $range = 'A:Z'; // Auto-expand

        // Append row
        $service->spreadsheets_values->append($sheetId, $range, new Google_Service_Sheets_ValueRange([
            'values' => [$rowData]
        ]), ['valueInputOption' => 'RAW']);

        return true;
    } catch (Exception $e) {
        error_log('Sheets Error: ' . $e->getMessage());
        return false;
    }
}

// IDs Sheet kamu (contoh)
define('SHEET_ID_MASUK', '1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms'); // Ganti!
define('SHEET_ID_KELUAR', '1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms'); // Ganti!

function saveSuratMasukToSheet($postData, $filename) {
    $row = [
        date('Y-m-d H:i:s'),
        $postData['nomor'] ?? '',
        $postData['nomor_surat'] ?? '',
        $postData['perihal'] ?? '',
        $postData['nama_pengirim'] ?? '',
        $postData['instansi'] ?? '',
        $postData['tanggal_surat'] ?? '',
        $filename
    ];
    return appendToGoogleSheet(SHEET_ID_MASUK, $row);
}

function saveSuratKeluarToSheet($postData, $filename) {
    $row = [
        date('Y-m-d H:i:s'),
        $postData['nomor'] ?? '',
        $postData['nomor_surat'] ?? '',
        $postData['perihal'] ?? '',
        $postData['ditujukan'] ?? '',
        $postData['instansi'] ?? '',
        $postData['nama_penerima'] ?? '',
        $postData['tanggal_surat_keluar'] ?? '',
        $filename
    ];
    return appendToGoogleSheet(SHEET_ID_KELUAR, $row);
}
?>
