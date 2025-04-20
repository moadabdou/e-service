<?php
require_once __DIR__ . '/../model.php';

class UserModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    // Get all professors in a department
    public function getProfessorsByDepartment(int $departmentId, int $profId): array {
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
                  WHERE p.id_deparetement = ? AND p.id_professor <> ?";

        if ($this->db->query($query, [$departmentId,$profId])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }
} 