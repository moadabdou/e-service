<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/entity/user.php";

session_start();
$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/coordonnateur"]);

$moduleModel   = new ModuleModel();
$filiereModel  = new FiliereModel();
$userModel     = new UserModel();

$coordinatorId = $_SESSION['id_user'] ?? null;
$assignments   = [];
$year          = $_GET['year'] ?? date('Y');

if ($coordinatorId) {
    $filiereId   = $filiereModel->getFiliereIdByCoordinator($coordinatorId);
    if ($filiereId) {

        $assignments = $moduleModel
            ->getAssignmentsByFiliereAndYearGrouped($filiereId, (int)$year);


        foreach ($assignments as &$modules) {
            foreach ($modules as &$mod) {
                $mod['professor_name'] = $userModel
                    ->getFullNameById($mod['id_professor']) ?? '---';
            }
        }
    }
}

ob_start();
include $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/coordonnateur/affectations_view.php";
$content = ob_get_clean();

$dashboard = new DashBoard();
$dashboard->view("professor/coordonnateur", "affectations", $content);
