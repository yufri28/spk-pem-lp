<?php
session_start();
require_once './../includes/header.php';

$data = $koneksi->query("SELECT a.nama_alternatif,
       MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.bobot_sub_kriteria END) AS C1,
       MAX(CASE WHEN k.id_kriteria = 'C2' THEN sk.bobot_sub_kriteria END) AS C2,
       MAX(CASE WHEN k.id_kriteria = 'C3' THEN sk.bobot_sub_kriteria END) AS C3,
       MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.bobot_sub_kriteria END) AS C4
FROM alternatif a
JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria
GROUP BY a.nama_alternatif
UNION ALL
SELECT 'min_max',
      MIN(CASE WHEN k.id_kriteria = 'C1' THEN sk.bobot_sub_kriteria END) AS C1,
       MIN(CASE WHEN k.id_kriteria = 'C2' THEN sk.bobot_sub_kriteria END) AS C2,
       MAX(CASE WHEN k.id_kriteria = 'C3' THEN sk.bobot_sub_kriteria END) AS C3,
       MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.bobot_sub_kriteria END) AS C4
FROM alternatif a
JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria;");

// Ambil hasil query ke dalam array
$results = $data->fetch_all(MYSQLI_ASSOC);

// Mempersiapkan array untuk menampung nilai
$beneficial_sum = ['C3' => 0, 'C4' => 0]; // Untuk menghitung jumlah kriteria yang menguntungkan
$cost_normalized_values = []; // Untuk menyimpan nilai normalisasi biaya

// Menghitung jumlah dan menyiapkan nilai untuk normalisasi
foreach ($results as $alternative) {
    if ($alternative['nama_alternatif'] !== 'min_max') { // Abaikan baris min_max
        $beneficial_sum['C3'] += $alternative['C3'];
        $beneficial_sum['C4'] += $alternative['C4'];
        
        // Menyimpan nilai untuk normalisasi biaya
        $cost_normalized_values[] = 1 / $alternative['C1']; // Normalisasi untuk C1 (Rumus 1/Langkah 1)
        $cost_normalized_values[] = 1 / $alternative['C2']; // Normalisasi untuk C2 (Rumus 1/Langkah 1)
    }
}

// Hitung total nilai normalisasi biaya untuk Rumus 2/Langkah 2
$cost_normalized_sum = array_sum($cost_normalized_values);

// Normalisasi
$normalized_data = [];
foreach ($results as $alternative) {
    if ($alternative['nama_alternatif'] !== 'min_max') { // Abaikan baris min_max
        $C1_normalized = 1 / $alternative['C1']; // Normalisasi untuk cost (Rumus 1/Langkah 1)
        $C2_normalized = 1 / $alternative['C2']; // Normalisasi untuk cost (Rumus 1/Langkah 1)
        
        $normalized_data[] = [
            'nama_alternatif' => $alternative['nama_alternatif'],
            'C1' => $C1_normalized / $cost_normalized_sum, // Normalisasi C1 (Rumus 2 /Langkah 2)
            'C2' => $C2_normalized / $cost_normalized_sum, // Normalisasi C2 (Rumus 2 /Langkah 2)
            'C3' => $alternative['C3'] / $beneficial_sum['C3'], // Normalisasi untuk benefit
            'C4' => $alternative['C4'] / $beneficial_sum['C4'], // Normalisasi untuk benefit
        ];
    }
}

// Hitung skor akhir (jumlah normalisasi)
$scored_data = [];
foreach ($normalized_data as $normalized) {
    $scored_data[] = [
        'nama_alternatif' => $normalized['nama_alternatif'],
        'skor' => $normalized['C1'] + $normalized['C2'] + $normalized['C3'] + $normalized['C4']
    ];
}

// Mengurutkan berdasarkan skor (dari besar ke kecil)
usort($scored_data, function($a, $b) {
    return $b['skor'] <=> $a['skor'];
});
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Normalisasi dan Peringkat</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <h2>Hasil Normalisasi dan Peringkat</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Alternatif</th>
                    <th>C1</th>
                    <th>C2</th>
                    <th>C3</th>
                    <th>C4</th>
                    <th>Skor</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($normalized_data as $index => $normalized): ?>
                <tr>
                    <td><?php echo $normalized['nama_alternatif']; ?></td>
                    <td><?php echo number_format($normalized['C1'], 4); ?></td>
                    <td><?php echo number_format($normalized['C2'], 4); ?></td>
                    <td><?php echo number_format($normalized['C3'], 4); ?></td>
                    <td><?php echo number_format($normalized['C4'], 4); ?></td>
                    <td><?php echo number_format($scored_data[$index]['skor'], 4); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Peringkat</h3>
        <ol>
            <?php foreach ($scored_data as $rank => $item): ?>
            <li><?php echo $item['nama_alternatif']; ?> - Skor: <?php echo number_format($item['skor'], 4); ?></li>
            <?php endforeach; ?>
        </ol>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>