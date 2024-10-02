<?php 

require_once '../config.php';
class Users{
    
    private $db;
    public function __construct()
    {
        $this->db = connectDatabase();
    }
    
    public function Index()
    {
        return $this->db->query("SELECT * FROM user");
    }
    public function HapusUser($id_user)
    {
        $stmtDelete = $this->db->prepare("DELETE FROM user WHERE id_user=?");
        $stmtDelete->bind_param("i",$id_user);
        $stmtDelete->execute();
        if ($stmtDelete->affected_rows > 0) {
            return $_SESSION['success'] = 'Data berhasil dihapus!';
        } else{
            return $_SESSION['error'] = 'Terjadi kesalahan dalam menghapus data.';
        }
        $stmtDelete->close();
    }

}

$users = new Users();
?>