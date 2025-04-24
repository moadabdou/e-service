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
