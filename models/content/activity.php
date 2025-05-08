<?php 
class ActivityModel extends Model{

    public function createActivity(string $content, string $icon = 'default'): string | false{
        
        if ($this->db->query("INSERT INTO activities(
                                                create_time, content, icon) VALUES (NOW(), ?, ?)", 
                                                [$content, $icon])) {    
            return $this->db->lastInsertId();

        }else {
            $this->error = $this->db->getError();
            var_dump($this->error);
            return false;
        }

    }

    public function getAllActivities(): array | bool{
        $activities = $this->db->query("SELECT id_activite, create_time, content, icon FROM activities ORDER BY create_time DESC", []);
        if ($activities) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        }else {
            $this->error = $this->db->getError();
            var_dump($this->error);
            return false;
        }
    }

    public function  getRecentActivities(int $limit): array | bool{
        $activities = $this->db->query("SELECT id_activite, create_time, content, icon FROM activities ORDER BY create_time DESC LIMIT ?", [$limit]);
        if ($activities) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        }else {
            $this->error = $this->db->getError();
            var_dump($this->error);
            return false;
        }
    }

}

?>