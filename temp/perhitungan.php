<?php
// Data alternatif
$data = [
    ['nama_alternatif' => 'Bendungan Raknamo', 'C1' => 4, 'C2' => 5, 'C3' => 3, 'C4' => 3],
    ['nama_alternatif' => 'Bukit Cinta', 'C1' => 3, 'C2' => 5, 'C3' => 3, 'C4' => 3],
    ['nama_alternatif' => 'Bukit Fatubraon', 'C1' => 2, 'C2' => 5, 'C3' => 1, 'C4' => 3],
    ['nama_alternatif' => 'Bukit Humon_Teletubies Lelogama', 'C1' => 1, 'C2' => 3, 'C3' => 3, 'C4' => 3],
    ['nama_alternatif' => 'Embung Oelomin', 'C1' => 1, 'C2' => 5, 'C3' => 2, 'C4' => 3],
    ['nama_alternatif' => 'Pantai Batu Nona', 'C1' => 3, 'C2' => 4, 'C3' => 3, 'C4' => 3],
    ['nama_alternatif' => 'Pantai Koepan', 'C1' => 5, 'C2' => 5, 'C3' => 3, 'C4' => 3],
    ['nama_alternatif' => 'Pantai Oesain', 'C1' => 1, 'C2' => 5, 'C3' => 3, 'C4' => 3],
    ['nama_alternatif' => 'Pantai Tablolong', 'C1' => 1, 'C2' => 4, 'C3' => 2, 'C4' => 3],
    ['nama_alternatif' => 'Taman Nostalgia', 'C1' => 2, 'C2' => 5, 'C3' => 3, 'C4' => 3],
];

// Nilai minimum dan maksimum untuk kriteria
$min_C1 = PHP_INT_MAX;
$min_C2 = PHP_INT_MAX;
$max_C3 = PHP_INT_MIN;
$max_C4 = PHP_INT_MIN;

// Mencari nilai minimum dan maksimum
foreach ($data as $alternative) {
    $min_C1 = min($min_C1, $alternative['C1']);
    $min_C2 = min($min_C2, $alternative['C2']);
    $max_C3 = max($max_C3, $alternative['C3']);
    $max_C4 = max($max_C4, $alternative['C4']);
}

// Normalisasi
$normalized_data = [];
foreach ($data as $alternative) {
    $normalized_data[] = [
        'nama_alternatif' => $alternative['nama_alternatif'],
        'C1' => $min_C1 / $alternative['C1'], // normalisasi untuk cost
        'C2' => $min_C2 / $alternative['C2'], // normalisasi untuk cost
        'C3' => $alternative['C3'] / $max_C3, // normalisasi untuk benefit
        'C4' => $alternative['C4'] / $max_C4, // normalisasi untuk benefit
    ];
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

// Tampilkan hasil normalisasi dan peringkat
echo "Nama Alternatif | C1 | C2 | C3 | C4 | Skor\n";
echo "----------------|----|----|----|----|------\n";
foreach ($normalized_data as $index => $normalized) {
    echo "{$normalized['nama_alternatif']} | {$normalized['C1']} | {$normalized['C2']} | {$normalized['C3']} | {$normalized['C4']} | {$scored_data[$index]['skor']}\n";
}

echo "\nPeringkat:\n";
foreach ($scored_data as $rank => $item) {
    echo ($rank + 1) . ". {$item['nama_alternatif']} - Skor: {$item['skor']}\n";
}
?>
