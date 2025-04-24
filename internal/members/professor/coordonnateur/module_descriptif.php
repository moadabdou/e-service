<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";

session_start();

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/coordonnateur"]);

$filiereModel = new FiliereModel();     










ob_start();
//html code
$content = ob_get_clean();
$dashboard = new Dashboard();
$dashboard->view("professor/coordonnateur", "Module_Descriptif",$content);







?>