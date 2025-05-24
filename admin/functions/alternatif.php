<?php 
    // session_start();
    require_once '../config.php';
    class Alternatif{

        private $db;

        public function __construct()
        {
            $this->db = connectDatabase();
        }

        public function getDataAlternatif() {
            $kriteriaList = $this->getKriteria();
            
            $select = "a.nama_alternatif, a.id_alternatif, a.latitude, a.longitude, a.alamat, a.gambar";
            foreach ($kriteriaList as $kriteria) {
                $kode = $kriteria['id_kriteria']; 
                $select .= ", MAX(CASE WHEN k.id_kriteria = '$kode' THEN kak.id_alt_kriteria END) AS id_alt_$kode";
                $select .= ", MAX(CASE WHEN k.id_kriteria = '$kode' THEN kak.f_id_sub_kriteria END) AS id_sub_$kode";
                $select .= ", MAX(CASE WHEN k.id_kriteria = '$kode' THEN sk.nama_sub_kriteria END) AS nama_$kode";
                $select .= ", MAX(CASE WHEN k.id_kriteria = '$kode' THEN sk.spesifikasi END) AS tema_$kode";
            }

            $sqlAlternatif = "
                SELECT $select
                FROM alternatif a
                JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
                JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
                JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria
                GROUP BY a.id_alternatif
                ORDER BY a.id_alternatif DESC
            ";
            $dataAlternatif = [];
            $resultAlt = $this->db->query($sqlAlternatif);
            while ($row = mysqli_fetch_assoc($resultAlt)) {
                $dataAlternatif[] = $row;
            }

            return $dataAlternatif;

        }

        public function getKriteria()
        {
            return $this->db->query("SELECT * FROM `kriteria`");
        }


        // public function getDataAlternatif()
        // {
        //     // return $this->db->query("SELECT * FROM alternatif");
        //     return $this->db->query("SELECT a.nama_alternatif, a.id_alternatif, a.latitude, a.longitude, a.alamat, a.gambar, kak.id_alt_kriteria,
        //         MAX(CASE WHEN k.id_kriteria = 'C1' THEN kak.id_alt_kriteria END) AS id_alt_C1,
        //         MIN(CASE WHEN k.id_kriteria = 'C2' THEN kak.id_alt_kriteria END) AS id_alt_C2,
        //         MIN(CASE WHEN k.id_kriteria = 'C3' THEN kak.id_alt_kriteria END) AS id_alt_C3,
        //         MAX(CASE WHEN k.id_kriteria = 'C4' THEN kak.id_alt_kriteria END) AS id_alt_C4,
        //         MAX(CASE WHEN k.id_kriteria = 'C1' THEN kak.f_id_sub_kriteria END) AS id_sub_C1,
        //         MIN(CASE WHEN k.id_kriteria = 'C2' THEN kak.f_id_sub_kriteria END) AS id_sub_C2,
        //         MIN(CASE WHEN k.id_kriteria = 'C3' THEN kak.f_id_sub_kriteria END) AS id_sub_C3,
        //         MAX(CASE WHEN k.id_kriteria = 'C4' THEN kak.f_id_sub_kriteria END) AS id_sub_C4,
        //         MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.nama_sub_kriteria END) AS nama_C1,
        //         MIN(CASE WHEN k.id_kriteria = 'C2' THEN sk.nama_sub_kriteria END) AS nama_C2,
        //         MIN(CASE WHEN k.id_kriteria = 'C3' THEN sk.nama_sub_kriteria END) AS nama_C3,
        //         MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.nama_sub_kriteria END) AS nama_C4
        //         FROM alternatif a
        //         JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
        //         JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
        //         JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria
        //         GROUP BY a.nama_alternatif ORDER BY a.id_alternatif DESC;");
        // }

        public function getAlternatifById($id_alternatif=null)
        {
            // return $this->db->query("SELECT * FROM alternatif");
            return $this->db->query("SELECT a.nama_alternatif, a.id_alternatif, a.latitude, a.longitude, a.alamat, a.gambar, kak.id_alt_kriteria,
                MAX(CASE WHEN k.id_kriteria = 'C1' THEN kak.id_alt_kriteria END) AS id_alt_C1,
                MIN(CASE WHEN k.id_kriteria = 'C2' THEN kak.id_alt_kriteria END) AS id_alt_C2,
                MIN(CASE WHEN k.id_kriteria = 'C3' THEN kak.id_alt_kriteria END) AS id_alt_C3,
                MAX(CASE WHEN k.id_kriteria = 'C4' THEN kak.id_alt_kriteria END) AS id_alt_C4,
                MAX(CASE WHEN k.id_kriteria = 'C1' THEN kak.f_id_sub_kriteria END) AS id_sub_C1,
                MIN(CASE WHEN k.id_kriteria = 'C2' THEN kak.f_id_sub_kriteria END) AS id_sub_C2,
                MIN(CASE WHEN k.id_kriteria = 'C3' THEN kak.f_id_sub_kriteria END) AS id_sub_C3,
                MAX(CASE WHEN k.id_kriteria = 'C4' THEN kak.f_id_sub_kriteria END) AS id_sub_C4,
                MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.nama_sub_kriteria END) AS nama_C1,
                MIN(CASE WHEN k.id_kriteria = 'C2' THEN sk.nama_sub_kriteria END) AS nama_C2,
                MIN(CASE WHEN k.id_kriteria = 'C3' THEN sk.nama_sub_kriteria END) AS nama_C3,
                MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.nama_sub_kriteria END) AS nama_C4
                FROM alternatif a
                JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
                JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
                JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria
                WHERE a.id_alternatif='$id_alternatif'
                GROUP BY a.nama_alternatif ORDER BY a.id_alternatif DESC;")->fetch_assoc();
        }

        public function tambahAlternatif($dataAlternatif, $dataSubKriteria)
        {
            // Cek jika alternatif sudah ada
            $namaAlternatif = strtolower($dataAlternatif['nama_alternatif']);
            $cekData = $this->db->query("SELECT * FROM alternatif WHERE LOWER(nama_alternatif) = '$namaAlternatif'");
            if ($cekData->num_rows > 0) {
                $_SESSION['error'] = 'Alternatif dengan nama yang sama sudah ada.';
                return;
            }

            // Simpan data alternatif
            $stmt = $this->db->prepare("INSERT INTO alternatif (nama_alternatif, latitude, longitude, alamat, gambar) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $dataAlternatif['nama_alternatif'], $dataAlternatif['latitude'], $dataAlternatif['longitude'], $dataAlternatif['alamat'], $dataAlternatif['gambar']);
            $stmt->execute();

            // Ambil ID alternatif terakhir
            $idAlternatif = $this->db->insert_id;

            // Simpan ke tabel kecocokan_alt_kriteria
            foreach ($dataSubKriteria as $idKriteria => $idSubKriteria) {
                $stmt2 = $this->db->prepare("INSERT INTO kecocokan_alt_kriteria (f_id_alternatif, f_id_kriteria, f_id_sub_kriteria) VALUES (?, ?, ?)");
                $stmt2->bind_param("iss", $idAlternatif, $idKriteria, $idSubKriteria);
                $stmt2->execute();
            }
        }

        // public function tambahAlternatif($dataAlternatif,$dataSubKriteria)
        // {
        //     $cekData = $this->db->query("SELECT * FROM `alternatif` WHERE LOWER(nama_alternatif) = '".strtolower($dataAlternatif['nama_alternatif'])."'");
        //     if ($cekData->num_rows > 0) {
        //         return $_SESSION['error'] = 'Data sudah ada!';
        //     } else {
        //         $stmtInsertAlt = $this->db->prepare("INSERT INTO alternatif(nama_alternatif, alamat, latitude, longitude, gambar) VALUES (?,?,?,?,?)");
        //         $stmtInsertAlt->bind_param("sssss", $dataAlternatif['nama_alternatif'], $dataAlternatif['alamat'], $dataAlternatif['latitude'], $dataAlternatif['longitude'], $dataAlternatif['gambar']);
        //         $stmtInsertAlt->execute();
        //         $getId = $this->db->query("SELECT id_alternatif FROM `alternatif` WHERE nama_alternatif = '".$dataAlternatif['nama_alternatif']."'");
        //         $fetchId = mysqli_fetch_assoc($getId);
        //         foreach ($dataSubKriteria as $key => $id_sub_kriteria) {
        //             $stmtInsertKecAltKriteria = $this->db->prepare("INSERT INTO kecocokan_alt_kriteria(f_id_alternatif, f_id_kriteria, f_id_sub_kriteria) VALUES (?,?,?)");
        //             $stmtInsertKecAltKriteria->bind_param("isi", $fetchId['id_alternatif'], $key, $id_sub_kriteria);
        //             $stmtInsertKecAltKriteria->execute();
        //         }
        //         if ($stmtInsertAlt->affected_rows > 0 && $stmtInsertKecAltKriteria->affected_rows > 0) {
        //             return $_SESSION['success'] = 'Data berhasil disimpan!';
        //         } else {
        //             return $_SESSION['error'] = 'Terjadi kesalahan dalam menyimpan data.';
        //         }
        //         $stmtInsertAlt->close();
        //         $stmtInsertKecAltKriteria->close();

        //     }
            

        // }
        // public function editAlternatif($dataAlternatif,$dataSubKriteria)
        // {
        //     // $stmtUpdateAlt = $this->db->prepare("UPDATE alternatif SET nama_alternatif=?, alamat=?, latitude=?, longitude=? WHERE id_alternatif=?");
        //     // $stmtUpdateAlt->bind_param("ssssi", $dataAlternatif['nama_alternatif'], $dataAlternatif['alamat'], $dataAlternatif['latitude'], $dataAlternatif['longitude'], $dataAlternatif['id_alternatif']);
        //     // $stmtUpdateAlt->execute();
        //     $query = "UPDATE alternatif SET nama_alternatif='" . $dataAlternatif['nama_alternatif'] . "', alamat='" . $dataAlternatif['alamat'] . "', latitude='" . $dataAlternatif['latitude'] . "', longitude='" . $dataAlternatif['longitude'] ."', gambar='" . $dataAlternatif['gambar'] . "' WHERE id_alternatif=" . $dataAlternatif['id_alternatif'];
        //     $stmtUpdateAlt = $this->db->query($query);
            
        //     if ($stmtUpdateAlt) {
        //         $getId = $this->db->query("SELECT id_alt_kriteria FROM `kecocokan_alt_kriteria` WHERE f_id_alternatif = '" . $dataAlternatif['id_alternatif'] . "'");
        //         $arr = [];
        //         while ($row = mysqli_fetch_row($getId)) {
        //             for ($i = 0; $i < count($row); $i++) {
        //                 array_push($arr, $row[$i]);
        //             }
        //         }
            
        //         for ($i = 0; $i < count($arr); $i++) {
        //             $queryKecKriteria = "UPDATE kecocokan_alt_kriteria SET f_id_alternatif='" . $dataAlternatif['id_alternatif'] . "', f_id_kriteria='C" . ($i + 1) . "', f_id_sub_kriteria='" . $dataSubKriteria[$i] . "' WHERE id_alt_kriteria=" . $arr[$i];
        //             $stmtUpdateKecKriteria = $this->db->query($queryKecKriteria);
        //         }
            
        //         if ($stmtUpdateKecKriteria) {
        //             $_SESSION['success'] = 'Data berhasil diubah!';
        //         } else {
        //             $_SESSION['error'] = 'Terjadi kesalahan dalam mengubah data.';
        //         }
        //     } else {
        //         $_SESSION['error'] = 'Terjadi kesalahan dalam mengubah data.';
        //     }
            
        //     // $stmtUpdateAlt->close();
            
        // }
        public function editAlternatif($dataAlternatif, $dataSubKriteria)
        {
            // Update data alternatif (gunakan prepared statement agar aman)
            $stmtUpdateAlt = $this->db->prepare(
                "UPDATE alternatif SET nama_alternatif=?, alamat=?, latitude=?, longitude=?, gambar=? WHERE id_alternatif=?"
            );
            $stmtUpdateAlt->bind_param(
                "sssssi",
                $dataAlternatif['nama_alternatif'],
                $dataAlternatif['alamat'],
                $dataAlternatif['latitude'],
                $dataAlternatif['longitude'],
                $dataAlternatif['gambar'],
                $dataAlternatif['id_alternatif']
            );
            $execAlt = $stmtUpdateAlt->execute();

            if ($execAlt) {
                // Ambil id_alt_kriteria terkait alternatif tersebut
                $getId = $this->db->query("SELECT id_alt_kriteria, f_id_kriteria FROM kecocokan_alt_kriteria WHERE f_id_alternatif = " . intval($dataAlternatif['id_alternatif']));
                
                if ($getId->num_rows > 0) {
                    // Update tiap baris kecocokan sesuai dengan kriteria dan dataSubKriteria
                    while ($row = $getId->fetch_assoc()) {
                        $idAltKriteria = $row['id_alt_kriteria'];

                 
                        $idKriteria = $row['f_id_kriteria']; // misal 'C1', 'C2', dst

                        // Ambil id_kriteria sebenarnya dari string, asumsikan format 'C<number>'
                        $numKriteria = intval(substr($idKriteria, 1));

                        // Ambil nilai sub-kriteria dari $dataSubKriteria dengan key id_kriteria (asumsi $dataSubKriteria key adalah id_kriteria integer)
                        // Karena $dataSubKriteria key-nya id_kriteria, harus cocok dengan $numKriteria
                        // Jika key-nya bukan numerik, sesuaikan dengan struktur yang kamu punya
                       
                        if (isset($dataSubKriteria[$idKriteria])) {
                            $idSubKriteria = $dataSubKriteria[$idKriteria];
                            // Update kecocokan alt_kriteria
                            $stmtUpdateKec = $this->db->prepare(
                                "UPDATE kecocokan_alt_kriteria SET f_id_sub_kriteria=? WHERE id_alt_kriteria=?"
                            );
                            $stmtUpdateKec->bind_param("ii", $idSubKriteria, $idAltKriteria);
                            $stmtUpdateKec->execute();
                            $stmtUpdateKec->close();
                        }
                    }
                    $_SESSION['success'] = 'Data berhasil diubah!';
                } else {
                    $_SESSION['error'] = 'Data kecocokan kriteria tidak ditemukan.';
                }
            } else {
                $_SESSION['error'] = 'Terjadi kesalahan dalam mengubah data alternatif.';
            }

            $stmtUpdateAlt->close();
        }

        public function hapusAlternatif($id) {
            $stmtDelete = $this->db->prepare("DELETE FROM alternatif WHERE id_alternatif=?");
            $stmtDelete->bind_param("i", $id);
            $stmtDelete->execute();

            if ($stmtDelete->affected_rows > 0) {
                $_SESSION['success'] = 'Data berhasil dihapus!';
            } else {
                $_SESSION['error'] = 'Terjadi kesalahan dalam menghapus data.';
            }
            $stmtDelete->close();
        }

        public function getSubJarakLokasi()
        {
            return $this->db->query("SELECT * FROM sub_kriteria WHERE f_id_kriteria = 'C1'");
        }
        public function getSubBiaya()
        {
            return $this->db->query("SELECT * FROM sub_kriteria WHERE f_id_kriteria = 'C2'");
        }
        public function getSubAkses()
        {
            return $this->db->query("SELECT * FROM sub_kriteria WHERE f_id_kriteria = 'C3'");
        }
        public function getSubTema()
        {
            return $this->db->query("SELECT * FROM sub_kriteria WHERE f_id_kriteria = 'C4'");
        }

    }
    $getDataAlternatif = new Alternatif();

?>