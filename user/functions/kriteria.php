<?php 

require_once '../config.php';
class Kriteria{
    private $db;

    public function __construct()
    {
        $this->db = connectDatabase();
    }

    public function getKriteriaByUser($id_user)
    {
        return $this->db->query("SELECT * FROM `kriteria` JOIN bobot_kriteria ON bobot_kriteria.f_id_user = '$id_user'");
    }
    public function getSubKriteriaJarak()
    {
        return $this->db->query("SELECT `sub_kriteria`.`nama_sub_kriteria`, `sub_kriteria`.`spesifikasi`, `sub_kriteria`.`bobot_sub_kriteria` FROM `sub_kriteria` JOIN `kriteria` ON `kriteria`.`id_kriteria`= `sub_kriteria`.`f_id_kriteria` WHERE `kriteria`.`id_kriteria`='C1';");
    }
    public function getKriteria()
    {
        return $this->db->query("SELECT * FROM `kriteria`");
    }

    public function getTema()
    {
        return $this->db->query("SELECT * FROM `sub_kriteria` WHERE f_id_kriteria='C4'");
    }

    public function getBobotKriteria($id_user=null)
    {
        return $this->db->query("SELECT * FROM bobot_kriteria WHERE f_id_user='$id_user'");
    }
    public function tambahBobotKriteria($dataBobotKriteria,$id_user)
    {
        $C1 = $dataBobotKriteria[0];
        $C2 = $dataBobotKriteria[1];
        $C3 = $dataBobotKriteria[2];
        $C4 = $dataBobotKriteria[3];
        $C5 = $dataBobotKriteria[4];
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
    public function editBobotKriteria($id_bobot=null,$dataBobotKriteria=null)
    {
        $C1 = $dataBobotKriteria[0];
        $C2 = $dataBobotKriteria[1];
        $C3 = $dataBobotKriteria[2];
        $C4 = $dataBobotKriteria[3];
        $C5 = $dataBobotKriteria[4];
        
        $update = $this->db->query("UPDATE bobot_kriteria SET C1=$C1,C2=$C2,C3=$C3,C4=$C4,C5=$C5 WHERE id_bobot='$id_bobot'");
        if($update){
            return $_SESSION['success'] = 'Data berhasil diedit!';
        }else{
            return $_SESSION['error'] = 'Terjadi kesalahan dalam menyimpan data.';
        }
    }
    // public function tambahTampung($dataTampung,$id_user)
    // {
    //     $C1 = $dataTampung[0];
    //     $C2 =  $dataTampung[1];
    //     $C3 =  $dataTampung[2];
    //     $C4 =  $dataTampung[3];
    //     $C5 =  $dataTampung[4];
    //     $this->db->query("INSERT INTO tabel_tampung (id, prio1, prio2, prio3, prio4, prio5, f_id_user) VALUES (NULL, '$C1', '$C2', '$C3', '$C4', '$C5', '$id_user')");
    // }
    // public function editTampung($id,$dataTampung)
    // {
    //     $C1 = $dataTampung[0];
    //     $C2 =  $dataTampung[1];
    //     $C3 =  $dataTampung[2];
    //     $C4 =  $dataTampung[3];
    //     $C5 =  $dataTampung[4];

    //     $this->db->query("UPDATE tabel_tampung SET prio1='$C1',prio2='$C2',prio3='$C3',prio4='$C4',prio5='$C5' WHERE id='$id'");
    // }

}


$Kriteria = new Kriteria();

?>