<?php
session_start();
require_once './../includes/header.php';

// Definisikan bobot kriteria
$weights = [
    'C1' => 0.4,
    'C2' => 0.3,
    'C3' => 0.1,
    'C4' => 0.2
];

$data = $koneksi->query("SELECT a.nama_alternatif, a.alamat, a.latitude, a.longitude, a.gambar,
       MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.bobot_sub_kriteria END) AS C1,
       MAX(CASE WHEN k.id_kriteria = 'C2' THEN sk.bobot_sub_kriteria END) AS C2,
       MAX(CASE WHEN k.id_kriteria = 'C3' THEN sk.bobot_sub_kriteria END) AS C3,
       MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.bobot_sub_kriteria END) AS C4
FROM alternatif a
JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria
GROUP BY a.nama_alternatif");

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
    $C1_normalized = 1 / $alternative['C1']; // Normalisasi untuk cost (Rumus 1/Langkah 1)
    $C2_normalized = 1 / $alternative['C2']; // Normalisasi untuk cost (Rumus 1/Langkah 1)
    
    $normalized_data[] = [
        'nama_alternatif' => $alternative['nama_alternatif'],
        'C1' => $C1_normalized / $cost_normalized_sum, // Normalisasi C1 (Rumus 2/Langkah 2)
        'C2' => $C2_normalized / $cost_normalized_sum, // Normalisasi C2 (Rumus 2/Langkah 2)
        'C3' => $alternative['C3'] / $beneficial_sum['C3'], // Normalisasi untuk benefit
        'C4' => $alternative['C4'] / $beneficial_sum['C4'], // Normalisasi untuk benefit
        'latitude' => $alternative['latitude'],
        'longitude' => $alternative['longitude'],
        'gambar' => $alternative['gambar']
    ];
}

// Menghitung bobot matriks
$weighted_data = [];
foreach ($normalized_data as $normalized) {
    $weighted_data[] = [
        'nama_alternatif' => $normalized['nama_alternatif'],
        'latitude' => $normalized['latitude'],
        'longitude' => $normalized['longitude'],
        'gambar' => $normalized['gambar'],
        'D1' => $normalized['C1'] * $weights['C1'],
        'D2' => $normalized['C2'] * $weights['C2'],
        'D3' => $normalized['C3'] * $weights['C3'],
        'D4' => $normalized['C4'] * $weights['C4'],
    ];
}

// Menghitung nilai fungsi optimalisasi Si
$optimal_values = [];
foreach ($weighted_data as $weighted) {
    $Si = $weighted['D1'] + $weighted['D2'] + $weighted['D3'] + $weighted['D4'];
    $optimal_values[] = [
        'nama_alternatif' => $weighted['nama_alternatif'],
        'latitude' => $weighted['latitude'],
        'longitude' => $weighted['longitude'],
        'gambar' => $weighted['gambar'],
        'Si' => $Si
    ];
}

// Menghitung peringkat
$max_Si = max(array_column($optimal_values, 'Si')); // Nilai terbesar Si
$ranked_values = [];
foreach ($optimal_values as $optimal) {
    $ranked_values[] = [
        'nama_alternatif' => $optimal['nama_alternatif'],
        'latitude' => $optimal['latitude'],
        'longitude' => $optimal['longitude'],
        'gambar' => $optimal['gambar'],
        'peringkat' => $optimal['Si'] / $max_Si // Menghitung tingkat peringkat
    ];
}

// Mengurutkan berdasarkan peringkat (dari besar ke kecil)
usort($ranked_values, function($a, $b) {
    return $b['peringkat'] <=> $a['peringkat'];
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
        <h2>Hasil Normalisasi</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Alternatif</th>

                    <th>C1</th>
                    <th>C2</th>
                    <th>C3</th>
                    <th>C4</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($normalized_data as $normalized): ?>
                <tr>
                    <td><?php echo $normalized['nama_alternatif']; ?></td>
                    <td><?php echo number_format($normalized['C1'], 4); ?></td>
                    <td><?php echo number_format($normalized['C2'], 4); ?></td>
                    <td><?php echo number_format($normalized['C3'], 4); ?></td>
                    <td><?php echo number_format($normalized['C4'], 4); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Nilai Si</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Alternatif</th>
                    <th>Si</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($optimal_values as $optimal): ?>
                <tr>
                    <td><?php echo $optimal['nama_alternatif']; ?></td>
                    <td><?php echo number_format($optimal['Si'], 4); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Hasil Peringkat</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Nama Alternatif</th>
                    <th>Gambar</th>
                    <th>Ki</th>
                    <th>Arah ke Google Maps</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0;?>
                <?php foreach ($ranked_values as $ranked): ?>
                <tr>
                    <td><?= ++$i; ?></td>
                    <td><?php echo $ranked['nama_alternatif']; ?></td>
                    <td>
                        <img src="../assets/img/<?php echo $ranked['gambar']; ?>" alt="Gambar"
                            style="width: 100px; height: auto;">
                    </td>
                    <td><?php echo number_format($ranked['peringkat'], 4); ?></td>
                    <td>
                        <a href="https://www.google.com/maps?q=<?php echo $ranked['latitude'] . ',' . $ranked['longitude']; ?>"
                            target="_blank" class="btn btn-primary">Lihat di Google Maps</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


</body>

</html>