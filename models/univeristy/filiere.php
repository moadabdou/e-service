<?php
require_once __DIR__ . "/../model.php";

class FiliereModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getFiliereIdByCoordinatorUserId($userId)
    {
        // Étape 1 : obtenir l'id du coordonnateur depuis le user
        $query = "SELECT id_coordonnateur FROM coordonnateur WHERE id_user = ?";
        if ($this->db->query($query, [$userId])) {
            $result = $this->db->fetch(PDO::FETCH_ASSOC);
            if (!$result) return null;

            $idCoordonnateur = $result['id_coordonnateur'];

            // Étape 2 : obtenir la filière associée
            $query2 = "SELECT id_filiere FROM filiere WHERE id_coordonnateur = ?";
            if ($this->db->query($query2, [$idCoordonnateur])) {
                $result2 = $this->db->fetch(PDO::FETCH_ASSOC);
                return $result2['id_filiere'] ?? null;
            }
        }

        return null;
    }
}
