<?php
require_once __DIR__."/../model.php"; 

class ModuleModel extends  Model{

    public function __construct() {
        parent::__construct();
    }

    // Modules disponibles pour le département du professeur
    public function getAvailableModulesByDepartment($departmentId, $professorId) {
        $query = "SELECT m.* 
                  FROM module m
                  JOIN filiere f ON m.id_filiere = f.id_filiere
                  WHERE f.id_deparetement = ?
                  AND m.id_module NOT IN (
                      SELECT id_module 
                      FROM affectation_professor 
                      WHERE to_professor = ?
                  )";
    
        if ($this->db->query( $query, [$departmentId, $professorId])){
            $this->db->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return $this->db->getError();
        }
    }
    

    // Modules déjà choisis par ce professeur
    public function getSelectedModulesByProfessor($professorId) {
        $query = "SELECT m.id_module, m.title, m.volume_horaire 
                  FROM module m
                  JOIN affectation_professor ap ON m.id_module = ap.id_module
                  WHERE ap.to_professor = ?";

        if ($this->db->query( $query, [$professorId])){
            $this->db->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return $this->db->getError();
        }
    }

    // Enregistrer les choix du professeur
    public function assignModulesToProfessor($professorId, $moduleIds) {
        try {
    
            // Get already assigned module IDs
            $query = "SELECT id_module FROM affectation_professor WHERE to_professor = ?";
            $existingModules = [];


            if ($this->db->query( $query, [$professorId])){
                $existingModules = $this->db->fetchAll(PDO::FETCH_ASSOC);
            }else {
                throw new Exception($this->db->getError());
            }
    
            // Debugging: Check the existingModules and moduleIds
            error_log("Existing modules: " . implode(", ", $existingModules));
            error_log("Modules to assign: " . implode(", ", $moduleIds));
    
            // Prepare insert statement
            // Create values part of the query for multiple inserts
            $values = [];
            $params = [];
            foreach ($moduleIds as $moduleId) {
                if (!in_array($moduleId, $existingModules)) {
                    $values[] = "(?, ?)";
                    $params[] = $professorId;
                    $params[] = $moduleId;
                    error_log("Adding module: $moduleId for professor: $professorId");
                } else {
                    error_log("Skipping module: $moduleId because it's already assigned.");
                }
            }

            if (!empty($values)) {
                $insertQuery = "INSERT INTO affectation_professor (to_professor, id_module) VALUES " . implode(", ", $values);
                if (! $this->db->query($insertQuery, $params)){
                    throw new Exception($this->db->getError());
                }
            }

            return true;
    
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }
    
    
}
?>
