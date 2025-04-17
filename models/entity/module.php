<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/core/Database.php";

class ModuleModel {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getPdoInstance();
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
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$departmentId, $professorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Modules déjà choisis par ce professeur
    public function getSelectedModulesByProfessor($professorId) {
        $query = "SELECT m.id_module, m.title, m.volume_horaire 
                  FROM module m
                  JOIN affectation_professor ap ON m.id_module = ap.id_module
                  WHERE ap.to_professor = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$professorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Enregistrer les choix du professeur
    public function assignModulesToProfessor($professorId, $moduleIds) {
        try {
            $this->conn->beginTransaction();
    
            // Get already assigned module IDs
            $query = "SELECT id_module FROM affectation_professor WHERE to_professor = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$professorId]);
            $existingModules = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
            // Debugging: Check the existingModules and moduleIds
            error_log("Existing modules: " . implode(", ", $existingModules));
            error_log("Modules to assign: " . implode(", ", $moduleIds));
    
            // Prepare insert statement
            $insertQuery = "INSERT INTO affectation_professor (to_professor, id_module) VALUES (?, ?)";
            $insertStmt = $this->conn->prepare($insertQuery);
    
            foreach ($moduleIds as $moduleId) {
                // Debugging: Check if moduleId is in existingModules
                if (!in_array($moduleId, $existingModules)) {
                    error_log("Inserting module: $moduleId for professor: $professorId");
                    $insertStmt->execute([$professorId, $moduleId]);
                } else {
                    error_log("Skipping module: $moduleId because it's already assigned.");
                }
            }
    
            $this->conn->commit();
            return true;
    
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }
    
    
}
?>
