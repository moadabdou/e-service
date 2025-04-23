<?php
require_once __DIR__ . '/../model.php';

class UserModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    // Get all professors in a department except the prof who is in the page
    public function getProfessorsByDepartmentex(int $departmentId, int $profId): array {
        $query = "SELECT 
                    u.id_user,
                    u.firstName,
                    u.lastName,
                    u.email,
                    u.phone,
                    u.CIN,
                    u.img as image_url,
                    u.role as u_role,
                    p.role as p_role,
                    p.min_hours,
                    p.max_hours
                  FROM user u
                  JOIN professor p ON u.id_user = p.id_professor
                  WHERE p.id_deparetement = ? AND p.id_professor <> ?";

        if ($this->db->query($query, [$departmentId,$profId])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }
        // Get all professors in a department
        public function getProfessorsByDepartment(int $departmentId): array {
            $query = "SELECT 
                        u.id_user,
                        u.firstName,
                        u.lastName,
                        u.email,
                        u.phone,
                        u.CIN,
                        u.role as u_role,
                        p.role as p_role,
                        p.min_hours,
                        p.max_hours
                      FROM user u
                      JOIN professor p ON u.id_user = p.id_professor
                      WHERE p.id_deparetement = ?";
    
            if ($this->db->query($query, [$departmentId])) {
                return $this->db->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return [];
            }
        }

        public function assignModuleToProfessor(int $moduleId, int $professorId): bool {
            $query = "INSERT INTO choix_module (id_module, by_professor, status, date_creation)
                      VALUES (?, ?, 'validated', NOW())";
    
            return $this->db->query($query, [$moduleId, $professorId]);
        }
} 