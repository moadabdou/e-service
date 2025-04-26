<?php 
require_once __DIR__ . "/../model.php"; 

class NoteModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function saveUploadedNote(int $moduleId, int $professorId, string $sessionType, string $fileId): array|false {
        $query = "INSERT INTO notes (id_module, id_professor, date_upload, session, file_id)
                  VALUES (?, ?, CURDATE(), ?, ?)";

        $success = $this->db->query($query, [$moduleId, $professorId, $sessionType, $fileId]);

        if ($success) {
            return [
                "success" => true,
                "file_id" => $fileId,
            ];
        } else {
            error_log("Erreur insertion note : " . $this->db->getError());
            return false;
        }
    }

        public function deleteNoteByFileId(string $fileId): bool {

            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/e-service/storage/pdfs/notes/';
            $filePath = $uploadDir . $fileId;  
    
            if (file_exists($filePath)) {
                unlink($filePath);
    
                $query = "DELETE FROM notes WHERE file_id = ?";
                $success = $this->db->query($query, [$fileId]);
    
                return $success; 
            }
    
            return false; 
        }
    
        public function updateNoteFile(string $fileId, string $NewId, array $newFile): array|false {
            $allowedExtensions = ['pdf', 'xlsx'];
            $fileExtension = pathinfo($newFile['name'], PATHINFO_EXTENSION);
        
            if (!in_array($fileExtension, $allowedExtensions)) {
                return false; 
            }
        
            $newFileName = $NewId . '.' . $fileExtension;
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/e-service/storage/pdfs/notes/';
            $uploadPath = $uploadDir . $newFileName;
        
            if (move_uploaded_file($newFile['tmp_name'], $uploadPath)) {
                $query = "UPDATE notes SET file_id = ? WHERE file_id = ?";
                $success = $this->db->query($query, [$newFileName, $fileId]);
        
                if ($success) {
                    $Exfile = $uploadDir . $fileId;  
                    if (file_exists($Exfile)) {
                        unlink($Exfile);
                    }
        
                    return [
                        "success" => true,
                        "file_id" => $newFileName,
                    ];
                } else {
                    if (file_exists($uploadPath)) {
                        unlink($uploadPath);
                    }
                }
            }
        
            return false; 
        }
        

    public function getNotesByProfessor(int $professorId): array {
        $query = "SELECT n.*, m.title AS module_title, f.title AS filiere_name 
                  FROM notes n
                  JOIN module m ON m.id_module = n.id_module
                  JOIN filiere f ON f.id_filiere = m.id_filiere
                  WHERE n.id_professor = ?
                  ORDER BY n.date_upload DESC";
    
        if ($this->db->query($query, [$professorId])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            error_log("Erreur getNotesByProfessor: " . $this->db->getError());
            return [];
        }
    }


    public function generateFileId(): string {
        return strval(random_int(100000000, 2147483647)); 
    }

    public function getRecentProfessorActivities(int $professorId): array {
    $activities = [];

    // 1. Notes envoyées
    $queryNotes = "SELECT n.date_upload AS date, m.title AS module_title, n.session
                   FROM notes n
                   JOIN module m ON m.id_module = n.id_module
                   WHERE n.id_professor = ?
                   ORDER BY n.date_upload DESC
                   LIMIT 6";
    if ($this->db->query($queryNotes, [$professorId])) {
        foreach ($this->db->fetchAll(PDO::FETCH_ASSOC) as $note) {
            $activities[] = [
                'type' => 'note_upload',
                'description' => "Notes envoyées pour <strong>" . htmlspecialchars($note['module_title']) . "</strong> (" . ucfirst($note['session']) . ").",
                'date' => $note['date']
            ];
        }
    }

    // 2. Choix de modules
    $queryChoices = "SELECT cm.date_creation AS date, m.title AS module_title
                     FROM choix_module cm
                     JOIN module m ON cm.id_module = m.id_module
                     WHERE cm.by_professor = ?
                     ORDER BY cm.date_creation DESC
                     LIMIT 6";
    if ($this->db->query($queryChoices, [$professorId])) {
        foreach ($this->db->fetchAll(PDO::FETCH_ASSOC) as $choice) {
            $activities[] = [
                'type' => 'module_choice',
                'description' => "Module <strong>" . htmlspecialchars($choice['module_title']) . "</strong> sélectionné.",
                'date' => $choice['date']
            ];
        }
    }

    // 3. Modules validés
    $queryAffectations = "SELECT ap.date_affectation AS date, m.title AS module_title
                          FROM affectation_professor ap
                          JOIN module m ON ap.id_module = m.id_module
                          WHERE ap.to_professor = ?
                          ORDER BY ap.date_affectation DESC
                          LIMIT 6";
    if ($this->db->query($queryAffectations, [$professorId])) {
        foreach ($this->db->fetchAll(PDO::FETCH_ASSOC) as $affectation) {
            $activities[] = [
                'type' => 'module_assigned',
                'description' => "Affectation du module <strong>" . htmlspecialchars($affectation['module_title']) . "</strong> confirmée.",
                'date' => $affectation['date']
            ];
        }
    }

    // 🌀 Maintenant, on trie toutes les activités par date DESC
    usort($activities, function ($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    // 🔥 Et on limite aux 5 plus récentes pour afficher dans le dashboard
    return array_slice($activities, 0, 6);
}

public function getNotesGroupedByYear(int $professorId): array {
    $query = "SELECT YEAR(date_upload) AS year, title, session, date_upload
              FROM notes n
              JOIN module m ON n.id_module = m.id_module
              WHERE id_professor = ?
              ORDER BY year DESC, date_upload DESC";

    if ($this->db->query($query, [$professorId])) {
        $notes = $this->db->fetchAll(PDO::FETCH_ASSOC);
        $grouped = [];

        foreach ($notes as $note) {
            $grouped[$note['year']][] = $note;
        }

        return $grouped;
    } else {
        return [];
    }
}

    
}

function getFileExtensionByType(string $fileType, string $fileId): string {
    $fileExtension = '';
    
    switch ($fileType) {
        case 'application/pdf':
            $fileExtension = '.pdf';
            break;
        case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
            $fileExtension = '.xlsx';
            break;
        case 'application/vnd.ms-excel':
            $fileExtension = '.xls';
            break;
        default:
            $fileExtension = ''; 
            break;
    }

    return $fileId . $fileExtension;
}
