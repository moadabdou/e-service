<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/deadline.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/components/search_filter_component.php";

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor","professor/chef_deparetement", "professor/coordonnateur"]);

$moduleModel = new ModuleModel();
$filiereModel = new FiliereModel();
$deadlineModel = new DeadlineModel();

$deadline = null;

if (!$deadlineModel->isFeatureOpen('choose_modules')) {
    $deadline = [
        "msg" => "Vous ne pouvez plus modifier ou supprimer vos modules sélectionnés, car la période de modification est terminée.",
        "type" => "danger",
    ];
}

$professorId = $_SESSION['id_user'] ?? null;
$departmentId = $_SESSION['id_deparetement'] ?? null;

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_module_id'])) {
    $moduleIdToDelete = intval($_POST['delete_module_id']);
    if ($professorId && $moduleIdToDelete) {
        $moduleModel->deleteModuleChoice($professorId, $moduleIdToDelete);
        $_SESSION['success_message'] = "Module supprimé avec succès.";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}

$selectedModules = $moduleModel->getSelectedModulesWithStatus($professorId);
$filliers = $filiereModel->getFilieresByDepartment($departmentId);

ob_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/professor/SelectedUnits.php";

$dashboard = new DashBoard();
$dashboard->view("chooseUnits", $content);
