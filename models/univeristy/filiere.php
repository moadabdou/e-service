<?php
require_once __DIR__ . "/../model.php";

class FiliereModel extends Model
{
    public function __construct()
    {
        parent::__construct();
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
