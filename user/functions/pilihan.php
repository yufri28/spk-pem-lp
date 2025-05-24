<?php
require_once '../../config.php';

$koneksi = connectDatabase();

// Query ambil id_kriteria dan nama_kriteria
$query = "SELECT id_kriteria, nama_kriteria FROM kriteria";
$result = $koneksi->query($query);

if (!$result) {
    die("Query error: " . $koneksi->error);
}

$allKriteria = [];
while ($row = $result->fetch_assoc()) {
    $allKriteria[] = [
        'id_kriteria' => $row['id_kriteria'],
        'nama' => $row['nama_kriteria']
    ];
}

// Ambil data prioritas yang sudah dipilih dari POST (misal array id_kriteria)
$selectedPrioritas = [];
if (isset($_POST['prioritas'])) {
    $selectedPrioritas = $_POST['prioritas'];
    if (!is_array($selectedPrioritas)) {
        $selectedPrioritas = [$selectedPrioritas];
    }
}

// Karena $allKriteria adalah array of array, kita buat dulu array id_kriteria untuk filtering
$allIds = array_column($allKriteria, 'id_kriteria');

// Filter: ambil id_kriteria yang belum dipilih
$availableIds = array_diff($allIds, $selectedPrioritas);

// Filter $allKriteria berdasarkan $availableIds supaya dropdown hanya menampilkan opsi yang belum dipilih
$availableOptions = array_filter($allKriteria, function($kriteria) use ($availableIds) {
    return in_array($kriteria['id_kriteria'], $availableIds);
});

// Output opsi dropdown
echo '<option value="">-- Pilih prioritas --</option>';
foreach ($availableOptions as $option) {
    echo '<option value="' . htmlspecialchars($option['id_kriteria']) . '">' . htmlspecialchars($option['nama']) . '</option>';
}
?>