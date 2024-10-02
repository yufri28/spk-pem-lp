<?php 
    // session_start();
    require_once '../config.php';
    class Alternatif{

        private $db;

        public function __construct()
        {
            $this->db = connectDatabase();
        }

        public function getDataAlternatif()
        {
            return $this->db->query("SELECT * FROM alternatif");
        }

        public function tambahAlternatif($dataAlternatif)
        {
            $stmtInsert = $this->db->prepare("INSERT INTO alternatif(nama_alternatif,alamat,latitude,longitude) VALUES(?,?,?,?)");
            $stmtInsert->bind_param("ssss", $dataAlternatif['nama_alternatif'],$dataAlternatif['alamat'],$dataAlternatif['latitude'],$dataAlternatif['longitude']);
            $stmtInsert->execute();
            if($stmtInsert){
                return $_SESSION['success'] = 'Data berhasil disimpan!';
            }else{
                return $_SESSION['error'] = 'Terjadi kesalahan dalam menyimpan data.';
            }
            $stmtInsert->close();
        }

        public function hapusAlternatif($id) {
            $stmtDelete = $this->db->prepare("DELETE FROM alternatif WHERE id_alternatif=?");
            $stmtDelete->bind_param("i", $id);
            $stmtDelete->execute();
            if($stmtDelete){
                return $_SESSION['success'] = 'Data berhasil dihapus!';
            }else{
                return $_SESSION['error'] = 'Terjadi kesalahan dalam menghapus data.';
            }
            $stmtDelete->close();
        }

    }

    $getDataAlternatif = new Alternatif();
?>