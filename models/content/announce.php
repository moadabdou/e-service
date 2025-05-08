<?php 

require_once __DIR__."/../model.php"; 

class AnnounceModel extends Model{

    public function createAnnounce(int $id_admin, string $title, string $content): string | false{
        
        if ($this->db->query("INSERT INTO announces(
                                                id_admin, create_time, title, content) VALUES (?,  NOW(), ?, ? )", 
                                                [$id_admin, $title, $content])) {    
            return $this->db->lastInsertId();

        }else {
            $this->error = $this->db->getError();
            var_dump($this->error);
            return false;
        }

    }

    public function getAllAnnounces(): array | bool{
        $announces = $this->db->query("SELECT * FROM announces ORDER BY create_time DESC", []);
        if ($announces) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        }else {
            $this->error = $this->db->getError();
            var_dump($this->error);
            return false;
        }
    }

    public function deleteAnnounce(int $id_announce, int $id_admin): bool {
        if ($this->db->query("DELETE FROM announces WHERE id_announce = ? AND  id_admin= ? ", [$id_announce, $id_admin])) {
            return true;
        } else {
            $this->error = $this->db->getError();
            var_dump($this->error);
            return false;
        }
    }

}

?>