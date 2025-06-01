<?php
require_once __DIR__ . "/../model.php";

class EmploiTempsUploadModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Enregistre un nouvel upload.
     */
    public function saveUpload(int $filiereId, string $semestre, int $annee, string $nom, string $chemin): bool
    {
        $sql = "INSERT INTO emploi_temps_upload
                (id_filiere, semestre, annee, nom_fichier, chemin_fichier)
                VALUES (?, ?, ?, ?, ?)";
        return $this->db->query($sql, [$filiereId, $semestre, $annee, $nom, $chemin]);
    }

    /**
     * Récupère la liste des uploads pour une filière + semestre + année.
     * Si tu passes null pour semestre ou année, ça n’applique pas le filtre.
     */
    public function getUploads(int $filiereId, ?string $semestre = null, ?int $annee = null): array
    {
        $sql = "SELECT * FROM emploi_temps_upload WHERE id_filiere = ?";
        $params = [$filiereId];

        if ($semestre) {
            $sql .= " AND semestre = ?";
            $params[] = $semestre;
        }
        if ($annee) {
            $sql .= " AND annee = ?";
            $params[] = $annee;
        }
        $sql .= " ORDER BY uploaded_at DESC";

        if (! $this->db->query($sql, $params)) {
            return [];
        }
        return $this->db->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllUploadsWithFiliere()
    {
        $query = "SELECT e.*, f.filiere_name 
              FROM emploi_temps_upload e
              JOIN filiere f ON e.id_filiere = f.id_filiere
              ORDER BY e.annee DESC, e.semestre ASC";

        if (!$this->db->query($query)) return [];
        return $this->db->fetchAll(PDO::FETCH_ASSOC);
    }
}
