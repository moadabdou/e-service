<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/chef_deparetement/modules_list_view.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/chef_deparetement"]);

$professorId = $_SESSION['id_user'];
$departmentId = $_SESSION['id_deparetement'] ?? null;

$moduleModel = new ModuleModel();
$FiliereModel = new FiliereModel();
$modules = $moduleModel->getAvailableModulesByDepartment($departmentId);
$filliere=$FiliereModel->getFilieresByDepartment($departmentId);
$alert = null;

$content = chefDepModulesListView($modules,$filliere);
$dashboard = new DashBoard();
$dashboard->view("professor/chef_deparetement", "modules", $content);
