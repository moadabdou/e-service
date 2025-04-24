<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/professor/upload_notes_view.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/notes.php";

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor","professor/chef_deparetement", "professor/coordonnateur"]);
$professorId = $_SESSION['id_user'];
$moduleModel = new ModuleModel();
$noteModel = new NoteModel();

$assignedModules = $moduleModel->getApprovedModulesByProfessor($professorId);
$info = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_module']) && isset($_POST['session_type'])) {
    if (!empty($_FILES['notes_file']['tmp_name'])) {
        $fileTmp = $_FILES['notes_file']['tmp_name'];
        $fileType = mime_content_type($fileTmp);
        $Id = $noteModel->generateFileId(); 

        $fileId = getFileExtensionByType($fileType, $Id);

        $allowedTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];

        if (in_array($fileType, $allowedTypes)) {
            $moduleId = intval($_POST['selected_module']);
            $sessionType = $_POST['session_type'];
            
            $saveResult = $noteModel->saveUploadedNote($moduleId, $professorId, $sessionType, $fileId);

            if ($saveResult && isset($saveResult['file_id'])) {
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/e-service/storage/pdfs/notes/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $targetFile = $uploadDir . $fileId;

                if (move_uploaded_file($fileTmp, $targetFile)) {
                    $info = ["type" => "success", "msg" => "Fichier de notes envoyé et enregistré avec succès."];
                } else {
                    $info = ["type" => "warning", "msg" => "Erreur lors de l'enregistrement du fichier sur le serveur."];
                }
            } else {
                $info = ["type" => "warning", "msg" => "Fichier envoyé mais non enregistré en base de données."];
            }
        } else {
            $info = ["type" => "danger", "msg" => "Format de fichier non autorisé."];
        }
    } else {
        $info = ["type" => "danger", "msg" => "Veuillez choisir un fichier à téléverser."];
    }
}

$content = uploadNotesView($assignedModules, $info);
$dashboard = new DashBoard();
$dashboard->view($_SESSION["role"], "UploadNotes", $content);




?>
