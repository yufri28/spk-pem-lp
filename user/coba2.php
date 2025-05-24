<?php
// Konfigurasi database
define('DB_HOST', 'localhost'); // Ganti dengan host database Anda
define('DB_USERNAME', 'root'); // Ganti dengan username database Anda
define('DB_PASSWORD', ''); // Ganti dengan password database Anda
define('DB_NAME', 'spk_pem_lp'); // Ganti dengan nama database Anda

// Fungsi untuk menghubungkan ke database
function connectDatabase()
{
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Koneksi database gagal: " . $conn->connect_error);
    }

    return $conn;
}

$koneksi = connectDatabase();

// Fungsi bantu query multiple rows
function queryAll($conn, $sql) {
    $result = $conn->query($sql);
    $rows = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    return $rows;
}

// --- Ambil data ---
$alternatif = queryAll($koneksi, "SELECT * FROM alternatif");
$kriteria = queryAll($koneksi, "SELECT * FROM kriteria");
$sub_kriteria = queryAll($koneksi, "SELECT * FROM sub_kriteria");
$kecocokan = queryAll($koneksi, "SELECT * FROM kecocokan_alt_kriteria");

// Map mudah akses bobot sub kriteria
$subBobotMap = [];
foreach ($sub_kriteria as $sk) {
    $subBobotMap[$sk['id_sub_kriteria']] = floatval($sk['bobot_sub_kriteria']);
}
// Map jenis kriteria (Benefit/Cost)
$kriteriaJenisMap = [];
foreach ($kriteria as $kr) {
    $kriteriaJenisMap[$kr['id_kriteria']] = $kr['jenis_kriteria'];
}

// --- 1. Pembentukan Decision Matrix ---
$decisionMatrix = [];
foreach ($alternatif as $alt) {
    foreach ($kriteria as $kr) {
        $nilai = 0;
        foreach ($kecocokan as $kec) {
            if ($kec['f_id_alternatif'] == $alt['id_alternatif'] && $kec['f_id_kriteria'] == $kr['id_kriteria']) {
                $nilai = $subBobotMap[$kec['f_id_sub_kriteria']];
                break;
            }
        }
        $decisionMatrix[$alt['id_alternatif']][$kr['id_kriteria']] = $nilai;
    }
}

// Tampilkan Decision Matrix
echo "<h2>1. Decision Matrix</h2>";
echo "<table border='1' cellpadding='6' cellspacing='0' style='border-collapse:collapse;'>";
echo "<thead><tr><th>ID Alternatif</th>";
foreach ($kriteria as $kr) {
    echo "<th>" . htmlspecialchars($kr['nama_kriteria']) . "</th>";
}
echo "</tr></thead>";
foreach ($alternatif as $alt) {
    echo "<tr><td>" . htmlspecialchars($alt['nama_alternatif']) . "</td>";
    foreach ($kriteria as $kr) {
        echo "<td>" . number_format($decisionMatrix[$alt['id_alternatif']][$kr['id_kriteria']], 4) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";


// --- 2. Normalisasi Decision Matrix ---
$maxKriteria = [];
$minKriteria = [];
foreach ($kriteria as $kr) {
    $values = [];
    foreach ($alternatif as $alt) {
        $v = $decisionMatrix[$alt['id_alternatif']][$kr['id_kriteria']];
        if ($v > 0) $values[] = $v;
    }
    $maxKriteria[$kr['id_kriteria']] = count($values) ? max($values) : 0;
    $minKriteria[$kr['id_kriteria']] = count($values) ? min($values) : 0;
}

$normalizedMatrix = [];
foreach ($alternatif as $alt) {
    foreach ($kriteria as $kr) {
        $val = $decisionMatrix[$alt['id_alternatif']][$kr['id_kriteria']];
        // Hindari pembagian 0 atau pembagian dengan nol
        if ($val == 0) {
            // Untuk Cost, nilai kecil sama dengan min, Benefit 0
            if ($kriteriaJenisMap[$kr['id_kriteria']] == 'Cost') {
                $val = $minKriteria[$kr['id_kriteria']] ?: 1;
            } else {
                $val = 0;
            }
        }
        if ($kriteriaJenisMap[$kr['id_kriteria']] == 'Benefit') {
            $normalizedMatrix[$alt['id_alternatif']][$kr['id_kriteria']] = $maxKriteria[$kr['id_kriteria']] ? $val / $maxKriteria[$kr['id_kriteria']] : 0;
        } elseif ($kriteriaJenisMap[$kr['id_kriteria']] == 'Cost') {
            $normalizedMatrix[$alt['id_alternatif']][$kr['id_kriteria']] = $val ? $minKriteria[$kr['id_kriteria']] / $val : 0;
        } else {
            $normalizedMatrix[$alt['id_alternatif']][$kr['id_kriteria']] = 0;
        }
    }
}

// Tampilkan normalisasi matrix
echo "<h2>2. Normalized Decision Matrix</h2>";
echo "<table border='1' cellpadding='6' cellspacing='0' style='border-collapse:collapse;'>";
echo "<thead><tr><th>ID Alternatif</th>";
foreach ($kriteria as $kr) {
    echo "<th>" . htmlspecialchars($kr['nama_kriteria']) . " (" . htmlspecialchars($kriteriaJenisMap[$kr['id_kriteria']]) . ")</th>";
}
echo "</tr></thead>";
foreach ($alternatif as $alt) {
    echo "<tr><td>" . htmlspecialchars($alt['nama_alternatif']) . "</td>";
    foreach ($kriteria as $kr) {
        echo "<td>" . number_format($normalizedMatrix[$alt['id_alternatif']][$kr['id_kriteria']], 4) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";

// --- 3. Menentukan bobot kriteria ---
// Kita asumsikan bobot kriteria sama, total 1
$totalK = count($kriteria);
// $bobotKriteria = [];
// foreach ($kriteria as $kr) {
//     $bobotKriteria[$kr['id_kriteria']] = 1/$totalK;
// }

$bobotKriteria = [
    'C1' => 0.4, 
    'C2' => 0.3, 
    'C3' => 0.2, 
    'C4' => 0.1
];

// Tampilkan bobot kriteria
echo "<h2>3. Bobot Kriteria</h2>";
echo "<table border='1' cellpadding='6' cellspacing='0' style='border-collapse:collapse;'>";
echo "<thead><tr><th>Kriteria</th><th>Jenis</th><th>Bobot</th></tr></thead>";
foreach ($kriteria as $kr) {
    echo "<tr>
    <td>" . htmlspecialchars($kr['nama_kriteria']) . "</td>
    <td>" . htmlspecialchars($kriteriaJenisMap[$kr['id_kriteria']]) . "</td>
    <td>" . number_format($bobotKriteria[$kr['id_kriteria']], 4) . "</td>
    </tr>";
}
echo "</table>";

// --- 4. Hitung nilai fungsi optimalisasi Si ---
$Si = [];
foreach ($alternatif as $alt) {
    $sum = 0;
    foreach ($kriteria as $kr) {
        $sum += $normalizedMatrix[$alt['id_alternatif']][$kr['id_kriteria']] * $bobotKriteria[$kr['id_kriteria']];
    }
    $Si[$alt['id_alternatif']] = $sum;
}

// Tampilkan nilai Si
echo "<h2>4. Nilai Fungsi Optimasi (Si)</h2>";
echo "<table border='1' cellpadding='6' cellspacing='0' style='border-collapse:collapse;'>";
echo "<thead><tr><th>ID Alternatif</th><th>Nama Alternatif</th><th>Nilai Si</th></tr></thead>";
foreach ($alternatif as $alt) {
    echo "<tr><td>" . htmlspecialchars($alt['id_alternatif']) . "</td>
    <td>" . htmlspecialchars($alt['nama_alternatif']) . "</td>
    <td>" . number_format($Si[$alt['id_alternatif']], 4) . "</td></tr>";
}
echo "</table>";

// --- 5. Peringkat berdasarkan nilai Si ---
arsort($Si);
echo "<h2>5. Peringkat Alternatif</h2>";
echo "<table border='1' cellpadding='6' cellspacing='0' style='border-collapse:collapse;'>";
echo "<thead><tr><th>Peringkat</th><th>ID Alternatif</th><th>Nama Alternatif</th><th>Nilai Si</th></tr></thead>";

$rank = 1;
foreach ($Si as $id_alt => $nilai) {
    $nama_alt = '';
    foreach ($alternatif as $alt) {
        if ($alt['id_alternatif'] == $id_alt) {
            $nama_alt = $alt['nama_alternatif'];
            break;
        }
    }
    echo "<tr>
    <td style='text-align:center;'>$rank</td>
    <td>" . htmlspecialchars($id_alt) . "</td>
    <td>" . htmlspecialchars($nama_alt) . "</td>
    <td style='text-align:right;'>" . number_format($nilai, 4) . "</td>
    </tr>";
    $rank++;
}

// Tutup koneksi
mysqli_close($koneksi);
?>