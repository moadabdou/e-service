<?php
require_once __DIR__."/../model.php"; 

class ModuleModel extends  Model{

    public function __construct() {
        parent::__construct();
    }

    
    public function getAvailableModulesByDepartment($departmentId, $professorId) {
        $query = "SELECT m.* 
                FROM module m
                JOIN filiere f ON m.id_filiere = f.id_filiere
                WHERE f.id_deparetement = ? 
                AND m.id_module NOT IN (
                    SELECT id_module 
                    FROM choix_module 
                    WHERE by_professor = ?
                )";
        if ($this->db->query($query, [$departmentId, $professorId])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return $this->db->getError();
        }
    }


    public function getSelectedModulesByProfessor($professorId) {
        $query = "SELECT m.id_module, m.title, m.volume_horaire 
                FROM module m
                JOIN choix_module cm ON m.id_module = cm.id_module
                WHERE cm.by_professor = ?";
        if ($this->db->query($query, [$professorId])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return $this->db->getError();
        }
    }

    
    public function getModuleById($moduleId) {
        $query = "SELECT id_module, title, volume_horaire FROM module WHERE id_module = ?";
        if ($this->db->query($query, [$moduleId])) {
            return $this->db->fetch(PDO::FETCH_ASSOC); // Return a single module as an associative array
        } else {
            return false; 
        }
    }
    
    public function getTotalHoursFromChoix($professorId) {
        $query = "SELECT SUM(m.volume_horaire) AS total 
                  FROM module m
                  JOIN choix_module cm ON m.id_module = cm.id_module
                  WHERE cm.by_professor = ?";
    
        if ($this->db->query($query, [$professorId])) {
            $result = $this->db->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } else {
            return 0;
        }
    }
    
    



    public function assignModulesToProfessor($professorId, $moduleIds) {
        try {
            $query = "SELECT id_module FROM choix_module WHERE by_professor = ?";
            $existingModules = [];

            if ($this->db->query($query, [$professorId])) {
                $existingModules = $this->db->fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception($this->db->getError());
            }
    
            error_log("Existing modules: " . implode(", ", $existingModules));
            error_log("Modules to assign: " . implode(", ", $moduleIds));
    
            
            $values = [];
            $params = [];
            $currentDate = date('Y-m-d'); 
            
            foreach ($moduleIds as $moduleId) {
                if (!in_array($moduleId, $existingModules)) {
                    $values[] = "(?, ?, ?, NULL, 'in progress')";
                    $params[] = $professorId;
                    $params[] = $moduleId;
                    $params[] = $currentDate;
                    error_log("Adding module: $moduleId for professor: $professorId");
                } else {
                    error_log("Skipping module: $moduleId because it's already assigned.");
                }
            }

            if (!empty($values)) {
                $insertQuery = "INSERT INTO choix_module (by_professor, id_module, date_creation, date_reponce, status) VALUES " . implode(", ", $values);
                if (!$this->db->query($insertQuery, $params)) {
                    throw new Exception($this->db->getError());
                }
            }

            return true;
    
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    public function getProfessorHours($professorId) {
        $query = "SELECT min_hours, max_hours FROM professor WHERE id_professor = ?";
        
        if ($this->db->query($query, [$professorId])) {
            return $this->db->fetch(PDO::FETCH_ASSOC); 
        } else {
            return false;
        }
    }
    

    
    public function getSelectedModulesWithStatus($professorId) {
        $query = "SELECT 
                    m.*, 
                    cm.status, 
                    CONCAT(u.firstName, ' ', u.lastName) AS user_full_name
                  FROM module m
                  JOIN choix_module cm ON m.id_module = cm.id_module
                  JOIN user u ON cm.by_professor = u.id_user
                  WHERE cm.by_professor = ?";
        
        if ($this->db->query($query, [$professorId])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return $this->db->getError();
        }
    }

    public function deleteModuleChoice($idUser, $idModule) {
        $query = "DELETE FROM choix_module WHERE by_professor = ? AND id_module = ?";
        
        if ($this->db->query($query, [$idUser, $idModule])) {
            return true;
        } else {
            return false;
        }
    }
    
    
}
    //functions for selected units
    function formatSemester($code) {
        $semesters = [
            's1' => 'Premier semestre',
            's2' => 'Deuxième semestre',
            's3' => 'Troisième semestre',
            's4' => 'Quatrième semestre',
            's5' => 'Cinquième semestre',
            's6' => 'Sixième semestre',
        ];
        return $semesters[strtolower($code)] ?? 'Semestre inconnu';
    }

    function getStatusBadge($status) {
        $commonClasses = 'badge px-3 py-2 fs-6 rounded-pill d-flex align-items-center gap-2 shadow-sm';

        switch ($status) {
            case 'validated':
                return '<span class="' . $commonClasses . ' bg-success text-white">
                            <i class="ti ti-circle-check"></i> Validé
                        </span>';
            case 'declined':
                return '<span class="' . $commonClasses . ' bg-danger text-white">
                            <i class="ti ti-circle-x"></i> Refusé
                        </span>';
            case 'in progress':
            default:
                return '<span class="' . $commonClasses . ' bg-warning text-dark">
                            <i class="ti ti-hourglass-empty"></i> En attente
                        </span>';
        }
    }


?>
