<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/notes.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/professor/upload_notes_single_view.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/professor/upload_notes_view.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor"]);

$professorId = $_SESSION['id_user'];
$moduleModel = new ModuleModel();
$noteModel = new NoteModel();

$info = null;

// Get module ID from GET
$moduleId = isset($_GET['module']) ? intval($_GET['module']) : null;

// Validate module ID
if (!$moduleId) {
    header("Location: /e-service/internal/members/professor/mes-modules.php");
    exit;
}

// Fetch module info
$module = $moduleModel->getModuleById($moduleId);

// Check if module belongs to professor
if (!$module) {
    header("Location: /e-service/internal/members/professor/mes-modules.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['session_type']) && isset($_FILES['notes_file'])) {
    if (!empty($_FILES['notes_file']['tmp_name'])) {
        $fileTmp = $_FILES['notes_file']['tmp_name'];
        $fileType = mime_content_type($fileTmp);
        $fileId = $noteModel->generateFileId();
        $fileName = getFileExtensionByType($fileType, $fileId);

        $allowedTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];

        if (in_array($fileType, $allowedTypes)) {
            $sessionType = $_POST['session_type'];

            $saveResult = $noteModel->saveUploadedNote($moduleId, $professorId, $sessionType, $fileId);

            if ($saveResult) {
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/e-service/storage/pdfs/notes/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $targetFile = $uploadDir . $saveResult['stored_file_name'];

                if (move_uploaded_file($fileTmp, $targetFile)) {
                    $info = ["type" => "success", "msg" => "Fichier de notes envoyé avec succès."];
                } else {
                    $info = ["type" => "danger", "msg" => "Erreur lors de l'enregistrement du fichier."];
                }
            } else {
                $info = ["type" => "danger", "msg" => "Erreur lors de l'enregistrement en base de données."];
            }
        } else {
            $info = ["type" => "danger", "msg" => "Format de fichier non autorisé."];
        }
    } else {
        $info = ["type" => "danger", "msg" => "Veuillez sélectionner un fichier."];
    }
}

$content = uploadSingleNoteView($module, $info);
$dashboard = new DashBoard();
$dashboard->view("professor", "UploaderNote", $content);

// Helper

?>