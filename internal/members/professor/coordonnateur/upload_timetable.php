<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/EmploiTempsUploadModel.php";

session_start();

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/coordonnateur"]);

$filiereModel = new FiliereModel();
$uploadModel  = new EmploiTempsUploadModel();

$coordId     = $_SESSION['id_user'] ?? null;
$filiereId   = $filiereModel->getFiliereIdByCoordinator($coordId);
$annee       = $_POST['annee'] ?? date('Y');
$semestre    = $_POST['semestre'] ?? 's1';
$message     = null;

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_FILES['timetable_file']) &&
    $_FILES['timetable_file']['error'] === UPLOAD_ERR_OK &&
    $_FILES['timetable_file']['size'] > 0
) {
    $file = $_FILES['timetable_file'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, ['pdf', 'xls', 'xlsx'])) {
        $message = ['type' => 'danger', 'text' => 'Seuls les fichiers PDF, XLS et XLSX sont autorisés.'];
    } else {
        $destDir = $_SERVER['DOCUMENT_ROOT'] . "/e-service/storage/Pdfs-Excels/Emplois/";
        if (!is_dir($destDir)) mkdir($destDir, 0755, true);

        $newName    = uniqid('emploi_') . '.' . $ext;
        $destPath   = $destDir . $newName;
        $publicPath = "/e-service/storage/Pdfs-Excels/Emplois/" . $newName;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            $uploadModel->saveUpload(
                $filiereId,
                $semestre,
                (int)$annee,
                $file['name'],
                $publicPath
            );
            $message = ['type' => 'success', 'text' => 'Fichier importé avec succès.'];
        } else {
            $message = ['type' => 'danger', 'text' => 'Échec du déplacement du fichier.'];
        }
    }
}

$uploads = $uploadModel->getUploads($filiereId, $semestre, (int)$annee);

ob_start();
include $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/coordonnateur/timetable_form.php";
$content = ob_get_clean();

$dashboard = new DashBoard();
$dashboard->view("professor/coordonnateur", "emploiTemps", $content);
