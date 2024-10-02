<?php 

require_once '../config.php';
class Kriteria{
    private $db;

    public function __construct()
    {
        $this->db = connectDatabase();
    }

    public function getKriteria($id_user)
    {
        return $this->db->query("SELECT * FROM `kriteria` JOIN bobot_kriteria ON bobot_kriteria.f_id_user = '$id_user'");
    }
    public function tambahBobotKriteria($dataPenilaian,$id_user)
    {
        $C1 = 0;
        $C2 = 0;
        $C3 = 0;
        $C4 = 0;
        $C5 = 0;
        foreach ($dataPenilaian as $key => $value) {
           switch ($key) {
            case "Fasilitas":
                $C1 = $value;
                break;
            case "Jarak":
                $C2 = $value;
                break;
            case "Biaya":
                $C3 = $value;
                break;
            case "Luas Kamar":
                $C4 = $value;
                break;
            case "Keamanan":
                $C5 = $value;
                break;
           }
        }
        $stmt = $this->db->prepare("SELECT * FROM bobot_kriteria WHERE f_id_user=?");
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows <= 0) {
            $stmtInsert = $this->db->query("INSERT INTO bobot_kriteria (id_bobot, C1, C2, C3, C4, C5, f_id_user) VALUES (NULL, '$C1', '$C2', '$C3', '$C4', '$C5', '$id_user')");
            if($stmtInsert){
                return $_SESSION['success'] = 'Data berhasil ditambahkan!';
            }else{
                return $_SESSION['error'] = 'Terjadi kesalahan dalam menyimpan data.';
            }
         } 
         else {
             return $_SESSION['error'] = 'Data sudah ada!';
         }
         $stmt->close();
    }
    public function editBobotKriteria($id_bobot,$dataPenilaian)
    {
        $C1 = 0;
        $C2 = 0;
        $C3 = 0;
        $C4 = 0;
        $C5 = 0;
        foreach ($dataPenilaian as $key => $value) {
           switch ($key) {
            case "Fasilitas":
                $C1 = $value;
                break;
            case "Jarak":
                $C2 = $value;
                break;
            case "Biaya":
                $C3 = $value;
                break;
            case "Luas Kamar":
                $C4 = $value;
                break;
            case "Keamanan":
                $C5 = $value;
                break;
           }
        }
        $update = $this->db->query("UPDATE bobot_kriteria SET C1=$C1,C2=$C2,C3=$C3,C4=$C4,C5=$C5 WHERE id_bobot='$id_bobot'");
        if($update){
            return $_SESSION['success'] = 'Data berhasil diedit!';
        }else{
            return $_SESSION['error'] = 'Terjadi kesalahan dalam menyimpan data.';
        }
    }
    public function tambahTampung($dataTampung,$id_user)
    {
        $C1 = $dataTampung[0];
        $C2 =  $dataTampung[1];
        $C3 =  $dataTampung[2];
        $C4 =  $dataTampung[3];
        $C5 =  $dataTampung[4];
        $this->db->query("INSERT INTO tabel_tampung (id, prio1, prio2, prio3, prio4, prio5, f_id_user) VALUES (NULL, '$C1', '$C2', '$C3', '$C4', '$C5', '$id_user')");
    }
    public function editTampung($id,$dataTampung)
    {
        $C1 = $dataTampung[0];
        $C2 =  $dataTampung[1];
        $C3 =  $dataTampung[2];
        $C4 =  $dataTampung[3];
        $C5 =  $dataTampung[4];

        $this->db->query("UPDATE tabel_tampung SET prio1='$C1',prio2='$C2',prio3='$C3',prio4='$C4',prio5='$C5' WHERE id='$id'");
    }

}


$Kriteria = new Kriteria();

?>