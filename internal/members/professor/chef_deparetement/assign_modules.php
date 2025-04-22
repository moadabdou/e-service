<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/chef_deparetement/assign_modules_view.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";


$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/chef_deparetement"]);

$moduleModel = new ModuleModel();
$FiliereModel = new FiliereModel();


$departmentId = $_SESSION['id_deparetement'] ?? null;
$chefId = $_SESSION['id_user'] ?? null;
$filliere=$FiliereModel->getFilieresByDepartment($departmentId);
if (!$departmentId || !$chefId) {
    die("Données de session manquantes.");
}

$info = null;

// Handle validation/decline actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $moduleId = intval($_POST['module_id'] ?? 0);
    $professorId = intval($_POST['professor_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($moduleId && $professorId && in_array($action, ['validate', 'decline'])) {
        // Define the status properly
        $status = $action === 'validate' ? 'validated' : 'declined';

        $success = $moduleModel->assignModuleToProfessor($moduleId, $professorId, $status);

        if ($success) {
            $_SESSION['info'] = [
                "type" => "success",
                "msg" => "Le module a été " . ($action === 'validate' ? "validé" : "refusé") . " avec succès."
            ];
        } else {
            $_SESSION['info'] = [
                "type" => "danger",
                "msg" => "Une erreur est survenue lors de la mise à jour."
            ];
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}

// Get modules to display after processing (or for initial page load)
$pendingModules = $moduleModel->getPendingModuleChoices($departmentId);

if (isset($_SESSION['info'])) {
    $info = $_SESSION['info'];
    unset($_SESSION['info']);
}

$content = pendingModulesView($pendingModules,$filliere, $info);
$dashboard = new DashBoard();
$dashboard->view("professor/chef_deparetement", "pendingModules", $content);
