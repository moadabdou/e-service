<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/chef_deparetement/history_view.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/chef_deparetement"]);

$moduleModel = new ModuleModel();
$departmentId = $_SESSION['id_deparetement'] ?? null;

if (!$departmentId) {
    die("ID de département manquant.");
}

$history = $moduleModel->getHistoricalAffectations($departmentId);

$content = yearHistoryView($history);

$dashboard = new DashBoard();
$dashboard->view($_SESSION['role'], "yearHistory", $content);
