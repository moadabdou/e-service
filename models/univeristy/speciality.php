<?php
require_once __DIR__ . "/../model.php";

class SpecialityModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll(): array|string|null
    {
        if ($this->db->query("SELECT s.id_speciality as id_speciality, CONCAT( s.title ,'/', d.title ) as title FROM speciality s JOIN deparetement d ON s.id_deparetement = d.id_deparetement")) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return $this->db->getError();
        }
    }

    public function getAllSpecialities()
    {
        if ($this->db->query("SELECT id_speciality, title FROM speciality")) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "Erreur SQL : " . $this->db->getError();
            exit;
        }
    }


    public function getDeparetementID(int $id_speciality): int|string|null
    {

        if ($this->db->query("SELECT d.id_deparetement  as id_deparetement FROM speciality s JOIN deparetement d ON s.id_deparetement = d.id_deparetement  WHERE  s.id_speciality = ?", [$id_speciality])) {
            $data = $this->db->fetch(PDO::FETCH_ASSOC);
            if ($data && !empty($data)) {
                return (int)$data["id_deparetement"];
            } else {
                return null;
            }
        } else {
            return $this->db->getError();
        }
    }
}
