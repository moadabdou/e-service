<?php
require_once __DIR__ . '/../model.php';

class FiliereModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getFilieresByDepartment(int $departmentId): array|string
    {
        $query = "SELECT id_filiere, title AS filiere_name
                  FROM filiere
                  WHERE id_deparetement = ?";

        if ($this->db->query($query, [$departmentId])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return "Query failed: " . $this->db->getError();
        }
    }
}
