<?php
require_once __DIR__ . "/../model.php"; 

class NoteModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function saveUploadedNote(int $moduleId, int $professorId, string $sessionType, int $fileId, string $fileName): array|false {
        $query = "INSERT INTO notes (id_module, id_professor, date_upload, session, file_id)
                  VALUES (?, ?, CURDATE(), ?, ?)";

        if ($this->db->query($query, [$moduleId, $professorId, $sessionType, $fileId])) {
            return ["success" => true, "file_id" => $fileId, "stored_file_name" => $fileName];
        } else {
            error_log("Erreur insertion note : " . $this->db->getError());
            return false;
        }
    }

    // Function to generate a unique file ID
    public function generateFileId(): string {
        return strval(random_int(100000000, 2147483647));
    }
}
?>
