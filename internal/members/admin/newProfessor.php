<?php
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/layouts/forms/forms.php";


$dashboard = new DashBoard();
$form = new Form();
$content = $form->professorFormView();

$dashboard->view("Admin", "newProfessor", $content);

?>