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
    public function getFiliereIdByCoordinator($coordonnateurId)
    {
        $query = "SELECT id_filiere FROM coordonnateur WHERE id_coordonnateur = ?";
        if ($this->db->query($query, [$coordonnateurId])) {
            $result = $this->db->fetch(PDO::FETCH_ASSOC);
            return $result['id_filiere'] ?? null;
        }
        return null;
    }
}
