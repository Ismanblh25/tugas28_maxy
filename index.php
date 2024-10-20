<?php
// Memanggil autoloader Composer
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Data penduduk contoh
$dataPenduduk = [
    ['Nama', 'Usia', 'Alamat', 'Pekerjaan'],
    ['Budi', 25, 'Jl. Mawar', 'Programmer'],
    ['Siti', 30, 'Jl. Melati', 'Dokter'],
    ['Andi', 22, 'Jl. Kamboja', 'Designer']
];

// Membuat Spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Memasukkan data penduduk ke lembar kerja (worksheet)
$sheet->fromArray($dataPenduduk, NULL, 'A1');

// Simpan ke file CSV
$writer = new Csv($spreadsheet);
$writer->save('data_penduduk.csv');

// Fitur pencarian penduduk
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $results = [];

    // Mencari nama yang sesuai dengan kata kunci
    foreach ($dataPenduduk as $index => $penduduk) {
        if ($index === 0) continue; // Lewati header
        if (stripos($penduduk[0], $search) !== false) {
            $results[] = $penduduk;
        }
    }

    // Menampilkan hasil pencarian
    if (count($results) > 0) {
        echo "<h3>Hasil Pencarian untuk '$search':</h3>";
        foreach ($results as $result) {
            echo implode(", ", $result) . "<br>";
        }
    } else {
        echo "Tidak ada hasil ditemukan untuk '$search'.";
    }
}

// Fitur ekspor ke format XLSX atau XLS
if (isset($_POST['export'])) {
    $format = $_POST['format'];

    if ($format == 'xlsx') {
        $writer = new Xlsx($spreadsheet);
        $writer->save('data_penduduk.xlsx');
        echo "Data berhasil diekspor ke XLSX.";
    } elseif ($format == 'xls') {
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
        $writer->save('data_penduduk.xls');
        echo "Data berhasil diekspor ke XLS.";
    }
}
?>

<!-- Form untuk pencarian penduduk -->
<form method="POST">
    <input type="text" name="search" placeholder="Cari nama...">
    <input type="submit" value="Cari">
</form>

<!-- Form untuk ekspor data -->
<form method="POST">
    <label for="format">Ekspor ke format:</label>
    <select name="format" id="format">
        <option value="csv">CSV</option>
        <option value="xlsx">XLSX</option>
        <option value="xls">XLS</option>
    </select>
    <input type="submit" name="export" value="Ekspor">
</form>
