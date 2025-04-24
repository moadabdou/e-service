<?php
require_once __DIR__ . "/../model.php";

class ModuleModel extends  Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getModulesByFiliereId($filiereId)
    {
        $query = "SELECT id_module, title, volume_horaire FROM module WHERE id_filiere = ?";
        if ($this->db->query($query, [$filiereId])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC); // Liste des modules de la filière
        } else {
            return []; // Retourne un tableau vide si erreur
        }
    }


    public function getAvailableModulesByDepartment($departmentId)
    {
        $query = "SELECT m.*,f.title AS filiere_name
                FROM module m
                JOIN filiere f ON m.id_filiere = f.id_filiere
                WHERE f.id_deparetement = ?  AND m.id_module
                NOT IN (( SELECT af.id_module FROM 
                affectation_professor af) UNION ( SELECT cm.id_module FROM 
                choix_module cm WHERE cm.by_professor= ? AND cm.status = 'in progress'))";
        if ($this->db->query($query, [$departmentId, $_SESSION['id_user']])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            var_dump($this->db->getError());
            return $this->db->getError();
        }
    }


    public function getAllModulesByDepartment($departmentId) {
                            $query = "SELECT m.*, 
                        f.title AS filiere_name
                    FROM module m
                    JOIN filiere f ON m.id_filiere = f.id_filiere
                    WHERE f.id_deparetement = ? ";

                            if ($this->db->query($query, [$departmentId])) {
                                return $this->db->fetchAll(PDO::FETCH_ASSOC);
                            } else {
                                return $this->db->getError();
                            }
                    }

    public function getSelectedModulesByProfessor($professorId)
    {
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

    public function getModuleById($moduleId)
    {
        $query = "SELECT id_module, title, volume_horaire FROM module WHERE id_module = ?";
        if ($this->db->query($query, [$moduleId])) {
            return $this->db->fetch(PDO::FETCH_ASSOC); // Return a single module as an associative array
        } else {
            return false;
        }
    }

    public function getTotalHoursFromChoix($professorId)
    {
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


    public function assignModulesToProfessor($professorId, $moduleIds)
    {
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

    public function affectModuleToProf($moduleId, $professorId) {
        $year = date("Y"); 

        require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/content/notification.php";
        $notificationModel = new NotificationModel();
        $professorId = intval($professorId);
        $moduleId = intval($moduleId);
        $chefId = intval($_SESSION['id_user']);
        
        $moduleQuery = "SELECT title FROM module WHERE id_module = ?";
        if ($this->db->query($moduleQuery, [$moduleId])) {
            $module = $this->db->fetch(PDO::FETCH_ASSOC);
            $moduleName = $module['title'];
        } else {
            return false; 
        }


        $query = "INSERT INTO affectation_professor (to_professor, by_chef_deparetement, id_module, annee) 
                  VALUES (?, ?, ?, ?)";
        
        if ($this->db->query($query, [$professorId, $chefId, $moduleId, $year])) {

    
            $notificationModel->createNotification(
                $professorId,
                "🎓 Module Affecté",
                "Le module '$moduleName' a été affecté à vous pour l'année $year par le chef de département.",
                null
            );
    
            return true;  
        } else {
            return false;  
        }
    }
    
    
    

    public function getProfessorHours($professorId)
    {
        $query = "SELECT min_hours, max_hours FROM professor WHERE id_professor = ?";

        if ($this->db->query($query, [$professorId])) {
            return $this->db->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }



    public function getSelectedModulesWithStatus($professorId)
    {
        $query = "SELECT 
                    m.id_module,
                    cm.status, 
                    m.title, 
                    m.description, 
                    m.semester, 
                    m.volume_horaire,
                    f.title AS filiere_name
                  FROM module m
                  JOIN filiere f ON f.id_filiere = m.id_filiere
                  JOIN choix_module cm ON m.id_module = cm.id_module
                  JOIN user u ON cm.by_professor = u.id_user
                  WHERE cm.by_professor = ?";

        if ($this->db->query($query, [$professorId])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            var_dump($this->db->getError());
            return [];
        }
    }

    public function deleteModuleChoice($idUser, $idModule)
    {
        $query = "DELETE FROM choix_module WHERE by_professor = ? AND id_module = ?";

        if ($this->db->query($query, [$idUser, $idModule])) {
            return true;
        } else {
            return false;
        }
    }

    public function getApprovedModulesByProfessor($professorId) {
            $query = "SELECT 
                        m.id_module, 
                        m.title, 
                        m.description, 
                        m.semester, 
                        m.volume_horaire, 
                        f.title AS filiere_name
                      FROM affectation_professor ap
                      JOIN module m ON ap.id_module = m.id_module
                      JOIN filiere f ON m.id_filiere = f.id_filiere
                      WHERE ap.to_professor = ?";
        
            // Execute the query with the professor ID
            if ($this->db->query($query, [$professorId])) {
                // Return the results as an associative array
                return $this->db->fetchAll(PDO::FETCH_ASSOC);
            } else {
                // Return false if there's an error
                return false;
            }
        }
        

    public function getUnassignedValidatedModules(int $departmentId): array {
        $query = "SELECT m.*, f.title AS filiere_name
                  FROM module m
                  JOIN filiere f ON m.id_filiere = f.id_filiere
                  WHERE f.id_deparetement = ?
                  AND m.id_module NOT IN (
                      SELECT id_module FROM choix_module WHERE status = 'validated'
                  )";

        if ($this->db->query($query, [$departmentId])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    function assignModuleToProfessor(int $moduleId, int $professorId, string $status): bool {
        $db = new Database();
        $db = new Database();
        require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/content/notification.php";
        $notificationModel = new NotificationModel();
        $db->beginTransaction();
    
        try {
            // Fetch the module title before using it in the notification
            $moduleQuery = "SELECT title FROM module WHERE id_module = ?";
            if ($db->query($moduleQuery, [$moduleId])) {
                $module = $db->fetch(PDO::FETCH_ASSOC);
                $moduleName = $module['title']; // Assign the module title to the variable
            } else {
                throw new Exception("Module not found.");
            }
    
            // Update the status of the module choice
            $updateQuery = "UPDATE choix_module SET status = ?, date_reponce = NOW()
                            WHERE id_module = ? AND by_professor = ?";
            $db->query($updateQuery, [$status, $moduleId, $professorId]);
    
            $chefId = $_SESSION['id_user'] ?? null;
            $year = date("Y");
    
            if (!$chefId) {
                throw new Exception("Chef ID manquant.");
            }
    
            if ($status === 'validated') {
                // Insert into affectation_professor table if the status is 'validated'
                $db->query(
                    "INSERT INTO affectation_professor (to_professor, by_chef_deparetement, id_module, annee)
                     VALUES (?, ?, ?, ?)",
                    [$professorId, $chefId, $moduleId, $year]
                );
    
                // Send notification to the professor about the module validation
                $notificationModel->createNotification(
                    $professorId,
                    "🎓 Module validé",
                    "Votre demande pour le module '$moduleName' a été validée pour l'année $year.",
                    null
                );
    
                // Decline the module choices of other professors if any
                $db->query(
                    "SELECT by_professor FROM choix_module 
                     WHERE id_module = ? AND by_professor != ? AND status = 'in progress' AND YEAR(date_creation) = ?",
                    [$moduleId, $professorId, $year]
                );
                $others = $db->fetchAll(PDO::FETCH_ASSOC);
    
                foreach ($others as $other) {
                    $otherProf = $other['by_professor'];
    
                    $db->query(
                        "UPDATE choix_module SET status = 'declined', date_reponce = NOW()
                         WHERE id_module = ? AND by_professor = ?",
                        [$moduleId, $otherProf]
                    );
                    // Send notification to the other professors whose choice was declined
                    $notificationModel->createNotification(
                        $otherProf,
                        "❌ Module refusé",
                        "Votre choix du module '$moduleName' pour l'année $year a été refusé car il a déjà été attribué.",
                        null
                    );
                }
            }
    
            $db->commit();
            return true;
    
        } catch (Exception $e) {
            $db->rollBack();
            error_log("Erreur assignModuleToProfessor: " . $e->getMessage());
            return false;
        }
    }
    
    
    
    public function getVacantModules(int $departmentId): array {
        $query = "SELECT m.*, f.title AS filiere_name 
                  FROM module m
                  JOIN filiere f ON m.id_filiere = f.id_filiere
                  WHERE f.id_deparetement = ?
                  AND m.id_module NOT IN (
                      SELECT id_module FROM choix_module
                  )
                  AND m.id_module NOT IN (
                      SELECT id_module FROM affectation_professor
                  )";
        
        return $this->db->query($query, [$departmentId]) ? $this->db->fetchAll(PDO::FETCH_ASSOC) : [];
    }
    
    
    public function getPendingModuleChoices(int $departmentId): array {
        $currentYear = date("Y");
    
        $query = "SELECT cm.id_module, cm.by_professor, cm.status, cm.date_creation,
                         m.title AS module_title, m.description, m.semester, m.volume_horaire,
                         f.title AS filiere_name,
                         u.firstName, u.lastName, u.email, u.phone
                  FROM choix_module cm
                  JOIN module m ON cm.id_module = m.id_module
                  JOIN filiere f ON m.id_filiere = f.id_filiere
                  JOIN user u ON cm.by_professor = u.id_user
                  WHERE cm.status = 'in progress'
                    AND f.id_deparetement = ?
                    AND YEAR(cm.date_creation) = ? ";
    
        if ($this->db->query($query, [$departmentId, $currentYear])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }
     
    
}
//functions for selected units
function formatSemester($code)
{
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

function getStatusBadge($status)
{
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
