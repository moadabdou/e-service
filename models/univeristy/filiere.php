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
    public function getAllFilieres(): array
    {
        $query = "SELECT id_filiere, title FROM filiere ORDER BY title ASC";

        if ($this->db->query($query)) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
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


    public function createFiliere(string $title, string $description, int $dep_id): string|false
    {
        if ($this->db->query(
            "INSERT INTO filiere(id_deparetement, title, description) VALUES (?, ?, ?)",
            [$dep_id, $title, $description]
        )) {

            return $this->db->lastInsertId();
        } else {

            $this->error = $this->db->getError();
            return false;
        }
    }

    public function getByID(int $filiere_id): array|string|null
    {
        if ($this->db->query("SELECT * FROM filiere WHERE id_filiere=?", [$filiere_id])) {
            return $this->db->fetch(PDO::FETCH_ASSOC);
        }else {
            throw new Exception($this->db->getError());
        }
    }


    public function getCoordinatorCondidates(int $id_filiere): array|string|null
    {

        $filiere_data = $this->getByID($id_filiere);

        if (!is_array($filiere_data)) {
            return $filiere_data;
        }

        $dep_id =  $filiere_data["id_deparetement"];

        if ($this->db->query("SELECT id_user, firstName, lastName, email, CONCAT('/e-service/internal/members/common/getResource.php?type=image&path=users_pp/', img) as img  FROM user JOIN professor ON id_professor = id_user WHERE  professor.role='normal' AND id_deparetement=?", [$dep_id])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return $this->db->getError();
        }
    }


    public function isQualifiedForCoordinator($professorId): bool
    {
        if ($this->db->query("SELECT COUNT(*) as count FROM professor WHERE id_professor = ? AND role = 'normal'", [$professorId])) {
            $result = $this->db->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        }
        return false;
    }

    public function getCoordinator(int $filiere_id): array|string|null
    {

        if ($this->db->query("SELECT id_user, firstName, lastName, email, CONCAT('/e-service/internal/members/common/getResource.php?type=image&path=users_pp/', img) as img  FROM user JOIN coordonnateur ON id_coordonnateur = id_user WHERE id_filiere=?", [$filiere_id])) {
            return $this->db->fetch(PDO::FETCH_ASSOC);
        } else {
            return $this->db->getError();
        }
    }

    public function countFilieres(): int
    {
        if ($this->db->query("SELECT COUNT(*) as count FROM filiere")) {
            $result = $this->db->fetch(PDO::FETCH_ASSOC);
            return (int)$result['count'];
        }
        return 0;
    }

    public function deleteCoordinator(int $id_filiere): bool
    {

        $this->db->beginTransaction();

        //get and remove the old coordinator
        if (!$this->db->query("SELECT id_coordonnateur FROM coordonnateur WHERE id_filiere = ?", [$id_filiere])) {
            $this->db->rollBack();
            return false;
        }

        $coordinator = $this->db->fetch();

        if (!$this->db->query("DELETE FROM coordonnateur WHERE id_filiere = ?", [$id_filiere])) {
            $this->db->rollBack();
            return false;
        }

        if ($coordinator) {
            // Reset the old coordinator's role to normal
            if (!$this->db->query("UPDATE professor SET role='normal' WHERE id_professor=?", [$coordinator['id_coordonnateur']])) {
                $this->db->rollBack();
                return false;
            }
        }

        $this->db->commit();
        return true;
    }

    public function updateFiliere(int $id_filiere, string $title, string $description): bool
    {
        if ($this->db->query("UPDATE filiere SET title = ?, description = ? WHERE id_filiere = ?", [$title, $description, $id_filiere])) {
            return true;
        } else {
            return false;
        }
    }
}
