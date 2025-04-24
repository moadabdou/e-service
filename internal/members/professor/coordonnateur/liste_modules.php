<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/coordonnateur"]);

$filiereModel = new FiliereModel();
$moduleModel  = new ModuleModel();

$coordinatorId = $_SESSION['id_user'];

$filiereId = $filiereModel->getFiliereIdByCoordinator($coordinatorId);
$modules   = $filiereId
    ? $moduleModel->getModulesByFiliereId($filiereId)
    : [];

ob_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/professor/coordonnateur/ModuleListing.php";
$content = ob_get_clean();

$dashboard = new DashBoard();
$dashboard->view("professor/coordonnateur", "ModuleListing", $content);
