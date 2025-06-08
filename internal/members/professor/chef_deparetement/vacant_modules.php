<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/chef_deparetement/vacant_modules_view.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/entity/professor.php";

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/chef_deparetement"]);

$moduleModel = new ModuleModel();
$FiliereModel = new FiliereModel();
$ProfessorModel = new ProfessorModel();

$departmentId = $_SESSION['id_deparetement'] ?? null;
$chefId = $_SESSION['id_user'] ?? null;

if (!$departmentId || !$chefId) {
    die("Données de session manquantes.");
}

$vacantModules = $moduleModel->getVacantModules($departmentId);
$filieres = $FiliereModel->getFilieresByDepartment($departmentId);
$availableProfessors = $ProfessorModel->getProfessorsByDepartment($departmentId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $moduleId = intval($_POST['manual_module_id'] ?? 0);
    $professorId = intval($_POST['manual_professor_id'] ?? 0);

    if ($moduleId && $professorId) {
        $success = $moduleModel->affectModuleToProf($moduleId, $professorId);

        if ($success) {
            $_SESSION['info'] = [
                "type" => "success",
                "msg" => "Le module a été affecté au professeur avec succès."
            ];
        } else {
            $_SESSION['info'] = [
                "type" => "danger",
                "msg" => "Une erreur est survenue lors de l'affectation du module."
            ];
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}

$content = vacantModulesView($vacantModules, $filieres, $availableProfessors);
$dashboard = new DashBoard();
$dashboard->view("vacantModules", $content);
