<?php 
require_once __DIR__."/../model.php"; 

class DepartementModel extends Model{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll(): array|string|null{
        if ($this->db->query("SELECT * FROM deparetement")){
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        }else {
            throw $this->db->getError(); //for now we gonna throw all  select queries
        }
    }

    public function getByID(int $id): array | string | null {

        if ($this->db->query("SELECT * FROM deparetement WHERE id_deparetement=?", [$id])){
            return $this->db->fetch(PDO::FETCH_ASSOC);
        }else {
            throw $this->db->getError();
        }

    }

    public function getHead($dep_id): array | string | null{
        if ($this->db->query("SELECT id_user, firstName, lastName, email, CONCAT('/e-service/internal/members/common/getResource.php?type=image&path=users_pp/', img) as img  FROM user JOIN professor ON id_professor = id_user WHERE id_deparetement=? AND professor.role='chef_deparetement'", [$dep_id])){
            return $this->db->fetch(PDO::FETCH_ASSOC);
        }else {
            return $this->db->getError();
        }
    }

    public function creatDepartement(string $title, string $description):string|false{
        if ($this->db->query("INSERT INTO deparetement(title, description) VALUES (?, ?)", 
            [$title, $description])) {
            
            return $this->db->lastInsertId();
            
        } else {

            $this->error = $this->db->getError();
            return false;

        }

    }   

    /**
     * gets professors with role normal
    */
    public function getHeadCondidates(int $dep_id): array | string | null{
        if ($this->db->query("SELECT id_user, firstName, lastName, email, CONCAT('/e-service/internal/members/common/getResource.php?type=image&path=users_pp/', img) as img  FROM user JOIN professor ON id_professor = id_user WHERE  professor.role='normal' AND id_deparetement=?", [$dep_id])){
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return $this->db->getError();
        }
    }

    /**
     * Checks if a professor is qualified to be department head
     * @param int $professorId The ID of the professor to check
     * @return bool True if qualified, false otherwise
     */
    public function isQualifiedForHead(int $professorId): bool {
        if ($this->db->query("SELECT COUNT(*) as count FROM professor WHERE id_professor = ? AND role = 'normal'", [$professorId])) {
            $result = $this->db->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        }
        return false;
    }

}

?>