<?php
require_once __DIR__ . "/../model.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/core/Database.php";

class ModuleModel extends  Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function createModule($data)
    {
        $title = addslashes($data['title']);
        $description = addslashes($data['description']);
        $volume_horaire = (int)$data['volume_horaire'];
        $semester = addslashes($data['semester']);
        $credits = (int)$data['credits'];
        $id_filiere = (int)$data['id_filiere'];
        $responsable = (int)$data['responsable'];
        $speciality = (int) $data['speciality'];

        $query = "INSERT INTO module (title, description, volume_horaire, semester, credits, id_filiere, responsable, speciality)
                  VALUES ('$title', '$description', $volume_horaire, '$semester', $credits, $id_filiere, $responsable, $speciality)";

        $result = $this->db->query($query);

        if (!$result) {
            echo $this->db->getError();
            return false;
        }
        return true;
    }

    /**
     * Récupère toutes les affectations validées (chef) pour une filière et une année,
     * et les regroupe par semestre.
     *
     * @param int   $filiereId  L'ID de la filière du coordonnateur
     * @param int   $year       L'année (ex. 2025)
     * @return array            Tableau ['s1'=>[module1, module2], 's2'=>[...], …]
     */
    public function getAssignmentsByFiliereAndYearGrouped($filiereId, $year)
    {
        $sql = "
      SELECT 
        m.id_module,
        m.code_module,
        m.title,
        m.semester,
        m.volume_cours, m.volume_td, m.volume_tp, m.volume_autre,
        m.credits,
        cm.to_professor AS id_professor
      FROM affectation_professor cm
      JOIN module m
        ON cm.id_module = m.id_module
      WHERE cm.annee = ?
        AND m.id_filiere = ?
      ORDER BY m.semester ASC, m.code_module ASC
    ";

        if (! $this->db->query($sql, [$year, $filiereId])) {
            return [];
        }

        $rows = $this->db->fetchAll(PDO::FETCH_ASSOC);
        $grouped = [];
        foreach ($rows as $r) {
            $sem = $r['semester'] ?? 'inconnu';
            $grouped[$sem][] = $r;
        }
        return $grouped;
    }





    public function getModulesByFiliereId($filiereId)
    {
        $query = "SELECT 
                id_module,
                code_module,
                title,
                volume_cours,
                volume_td,
                volume_tp,
                volume_autre,
                credits,
                semester,
                description,
                evaluation,
                responsable,
                id_speciality
              FROM module 
              WHERE id_filiere = ?";

        if ($this->db->query($query, [$filiereId])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
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


    public function getAllModulesByDepartment($departmentId)
    {
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
        $query = "SELECT m.id_module, m.title, m.volume_cours 
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
        $query = "SELECT id_module, title, volume_cours FROM module WHERE id_module = ?";
        if ($this->db->query($query, [$moduleId])) {
            return $this->db->fetch(PDO::FETCH_ASSOC); // Return a single module as an associative array
        } else {
            return false;
        }
    }

    public function getTotalHoursFromChoix($professorId)
    {
        $query = "SELECT SUM(m.volume_cours) AS total 
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

    public function affectModuleToProf($moduleId, $professorId)
    {
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
                    m.volume_cours,
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

    public function getApprovedModulesByProfessor(int $professorId): array|false
    {
        $query = "SELECT 
                    m.id_module, 
                    m.title, 
                    m.description, 
                    m.semester, 
                    m.volume_cours, 
                    f.title AS filiere_name,
                    (SELECT COUNT(*) 
                     FROM notes 
                     WHERE notes.id_module = m.id_module 
                       AND notes.id_professor = ?) AS notes_uploaded
                  FROM affectation_professor ap
                  JOIN module m ON ap.id_module = m.id_module
                  JOIN filiere f ON m.id_filiere = f.id_filiere
                  WHERE ap.to_professor = ?";

        // Execute the query with professorId twice (once for subquery and once for main query)
        if ($this->db->query($query, [$professorId, $professorId])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }



    public function getUnassignedValidatedModules(int $departmentId): array
    {
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

    function assignModuleToProfessor(int $moduleId, int $professorId, string $status): bool
    {
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



    public function getVacantModules(int $departmentId): array
    {
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


    public function getPendingModuleChoices(int $departmentId): array
    {
        $currentYear = date("Y");

        $query = "SELECT cm.id_module, cm.by_professor, cm.status, cm.date_creation,
                         m.title AS module_title, m.description, m.semester, m.volume_cours,
                         f.title AS filiere_name,
                         u.firstName, u.lastName, u.email, u.phone
                  FROM choix_module cm
                  JOIN module m ON cm.id_module = m.id_module
                  JOIN filiere f ON m.id_filiere = f.id_filiere
                  JOIN user u ON cm.by_professor = u.id_user
                  WHERE cm.status = 'in progress'
                    AND f.id_deparetement = ?
                    AND YEAR(cm.date_creation) = ?";

        if ($this->db->query($query, [$departmentId, $currentYear])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            var_dump($this->db->getError());
            return [];
        }
    }

    public function getTotalAssignedHoursByDepartment(int $departmentId): int
    {
        $query = "SELECT SUM(m.volume_cours) AS total_hours
                  FROM affectation_professor ap
                  JOIN module m ON ap.id_module = m.id_module
                  JOIN filiere f ON m.id_filiere = f.id_filiere
                  WHERE f.id_deparetement = ?";

        if ($this->db->query($query, [$departmentId])) {
            $result = $this->db->fetch(PDO::FETCH_ASSOC);
            return (int) ($result['total_hours'] ?? 0);
        } else {
            return 0;
        }
    }


    public function getHistoricalAffectations(int $departmentId): array
    {
        $query = "SELECT 
                    ap.annee,
                    u.id_user,
                    u.firstName,
                    u.lastName,
                    u.email,
                    u.img,
                    m.title AS module_title,
                    f.title AS filiere_name,
                    m.volume_cours
                  FROM affectation_professor ap
                  JOIN user u ON ap.to_professor = u.id_user
                  JOIN module m ON ap.id_module = m.id_module
                  JOIN filiere f ON m.id_filiere = f.id_filiere
                  WHERE f.id_deparetement = ?
                  ORDER BY ap.annee DESC, u.lastName";

        if ($this->db->query($query, [$departmentId])) {
            $results = $this->db->fetchAll(PDO::FETCH_ASSOC);
            $grouped = [];

            foreach ($results as $row) {
                $year = $row['annee'];
                $userId = $row['id_user'];

                if (!isset($grouped[$year])) {
                    $grouped[$year] = [];
                }

                if (!isset($grouped[$year][$userId])) {
                    $grouped[$year][$userId] = [
                        'firstName' => $row['firstName'],
                        'lastName' => $row['lastName'],
                        'email' => $row['email'],
                        'img' => $row['img'],
                        'modules' => []
                    ];
                }

                $grouped[$year][$userId]['modules'][] = [
                    'title' => $row['module_title'],
                    'filiere' => $row['filiere_name'],
                    'hours' => $row['volume_cours']
                ];
            }

            return $grouped;
        }

        return [];
    }


    // Get all modules in a department
    public function getModulesByDepartment(int $departmentId): array
    {
        $query = "SELECT id_module, title, volume_cours
                  FROM module
                  WHERE id_deparetement = ?";

        if ($this->db->query($query, [$departmentId])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Get a professor's assigned modules
    public function getProfessorModules(int $professorId): array
    {
        $query = "SELECT m.id_module, m.title, m.volume_cours
                  FROM module m
                  JOIN affectation_professor ap ON ap.id_module = m.id_module
                  WHERE ap.to_professor = ?";

        if ($this->db->query($query, [$professorId])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function getModulesWithoutNotes(int $professorId): array
    {
        $query = "SELECT m.* FROM module m
                  JOIN affectation_professor ap ON ap.id_module = m.id_module
                  WHERE ap.to_professor = ?
                  AND m.id_module NOT IN (SELECT id_module FROM notes WHERE id_professor = ?)";

        return $this->db->query($query, [$professorId, $professorId])
            ? $this->db->fetchAll(PDO::FETCH_ASSOC)
            : [];
    }

    public function getModuleChoicesGroupedByYear(int $professorId): array
    {
        $query = "SELECT YEAR(date_creation) AS year, m.title, cm.status, cm.date_creation
                  FROM choix_module cm
                  JOIN module m ON cm.id_module = m.id_module
                  WHERE cm.by_professor = ?
                  ORDER BY year DESC, cm.date_creation DESC";

        if ($this->db->query($query, [$professorId])) {
            $choices = $this->db->fetchAll(PDO::FETCH_ASSOC);
            $grouped = [];

            foreach ($choices as $choice) {
                $grouped[$choice['year']][] = $choice;
            }

            return $grouped;
        } else {
            return [];
        }
    }

    // from here :
    public function getNextModuleId(): int
    {
        $this->db->query("SHOW TABLE STATUS LIKE 'module'");
        $row = $this->db->fetch();
        return $row ? (int)$row['Auto_increment'] : 1;
    }

    public function generateUniqueEvaluationId(): int
    {
        $this->db->query("SELECT MAX(evaluation) AS max_val FROM module");
        $res = $this->db->fetch();
        return ($res && $res['max_val']) ? $res['max_val'] + 1 : 1;
    }

    public function getEvaluationIdByModuleId(int $id): int
    {
        $this->db->query("SELECT evaluation FROM module WHERE id_module = ?", [$id]);
        $res = $this->db->fetch();
        return $res ? (int)$res['evaluation'] : 0;
    }

    public function getParentModuleIdByEvaluation(int $eval): int
    {
        $this->db->query("SELECT MIN(id_module) AS id FROM module WHERE evaluation = ?", [$eval]);
        $res = $this->db->fetch();
        return $res ? (int)$res['id'] : 0;
    }

    public function countElementsByEvaluation(int $eval): int
    {
        $this->db->query("SELECT COUNT(*) AS total FROM module WHERE evaluation = ?", [$eval]);
        $res = $this->db->fetch();
        return $res ? (int)$res['total'] : 0;
    }

    public function getModulesWithUniqueEvaluationOnly(): array
    {
        $this->db->query(" SELECT m.* FROM module m
            WHERE evaluation != 0 AND evaluation IN (
                SELECT evaluation FROM module
                GROUP BY evaluation
                HAVING COUNT(*) = 1
            )
        ");

        $results = $this->db->fetchAll();

        return $results ?: [];
    }


    public function insertModule(array $data): bool
    {
        $query = "INSERT INTO module (title, description, volume_cours, volume_td, volume_tp, volume_autre, evaluation, semester, credits, id_filiere, code_module)
                  VALUES (:title, :description, :volume_cours, :volume_td, :volume_tp, :volume_autre, :evaluation, :semester, :credits, :id_filiere, :code_module)";
        return $this->db->query($query, $data);
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
