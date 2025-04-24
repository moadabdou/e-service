<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/chef_deparetement/assign_modules_view.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";


$userController = new UserController();
$userController->checkCurrentUserAuthority(allowedRoles: ["professor/chef_deparetement"]);

$moduleModel = new ModuleModel();
$FiliereModel = new FiliereModel();


$departmentId = $_SESSION['id_deparetement'] ?? null;
$chefId = $_SESSION['id_user'] ?? null;
$filliere=$FiliereModel->getFilieresByDepartment(departmentId: $departmentId);
if (!$departmentId || !$chefId) {
    die("Données de session manquantes.");
}

$info = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $moduleId = intval(value: $_POST['module_id'] ?? 0);
    $professorId = intval(value: $_POST['professor_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($moduleId && $professorId && in_array(needle: $action, haystack: ['validate', 'decline'])) {

        $status = $action === 'validate' ? 'validated' : 'declined';

        $success = $moduleModel->assignModuleToProfessor(moduleId: $moduleId, professorId: $professorId, status: $status);

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

$pendingModules = $moduleModel->getPendingModuleChoices($departmentId);

if (isset($_SESSION['info'])) {
    $info = $_SESSION['info'];
    unset($_SESSION['info']);
}

$content = pendingModulesView($pendingModules,$filliere, $info);
$dashboard = new DashBoard();
$dashboard->view("professor/chef_deparetement", "pendingModules", $content);
