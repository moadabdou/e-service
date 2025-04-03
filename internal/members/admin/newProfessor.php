<?php
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/layouts/forms/forms.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/entity/professor.php";

$form = new Form();

$errors = [];
$info = null;

if($_SERVER["REQUEST_METHOD"] ==  "POST"){
    if (empty($_POST['first_name']) || !preg_match("/^[a-zA-Z '\-]*$/", $_POST['first_name'])) {
        $errors["first_name"] = "First name is required and should contain only letters , spaces,',-";
    }

    if (empty($_POST['last_name']) || !preg_match("/^[a-zA-Z '\-]*$/", $_POST['last_name'])) {
        $errors["last_name"] = "Last name is required and should contain only letters , spaces,',-";
    }

    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "A valid email address is required";
    }

    if (empty($_POST['phone']) || !preg_match("/^[0-9]{10}$/", $_POST['phone'])) {
        $errors["phone"] = "Phone number is required and should be 10 digits";
    }

    if (empty($_POST['address'])) {
        $errors["address"] = "Address is required";
    }

    if (empty($_POST['cin']) || !preg_match("/^[A-Z]{1,2}[0-9]{5,6}$/", $_POST['cin'])) {
        $errors["cin"] = "CIN is required and should be 7 characters of uppercase letters and numbers (e.g R123456)";
    }

    if (empty($_POST['birth_date']) || !strtotime($_POST['birth_date']) || (time() - strtotime($_POST['birth_date'])) < (24 * 365 * 24 * 60 * 60)) {
        $errors["birth_date"] = "A valid birth date is required (older  than 24 yrs old)";
    }

    if (empty($_POST['departement']) || !is_numeric($_POST['departement']) || $_POST['departement'] < 0 ) {
        $errors["departement"] = "Department is required and must be a valid ID";
    }

    if (empty($_POST['work_hours_max']) || !is_numeric($_POST['work_hours_max']) || $_POST['work_hours_max'] < 0) {
        $errors["work_hours_max"] = "Maximum work hours must be a positive number";
    }

    if (empty($_POST['work_hours_min']) || !is_numeric($_POST['work_hours_min']) || $_POST['work_hours_min'] < 0) {
        $errors["work_hours_min"] = "Minimum work hours must be a positive number";
    }

    if (count($errors)){
        $info =  [
            "msg" => "we encountred some errors when we tried to add this professor",
            "type" => "danger"
        ];
    }else {
        $profModel = new ProfessorModel();

        $feedback_new_prof = $profModel->newProfessor(
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['cin'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['address'],
            $_POST['birth_date'],
            $_POST['departement'],
            $_POST['work_hours_max'],
            $_POST['work_hours_min']
        );

        if ($feedback_new_prof){
            $info =  [
                "msg" => "the  registration was seccussfull,  an email  is sent to the professor",
                "type" => "info"
            ];
        }else {
            $info =  [
                "msg" => "data format looks fine but an error accured when we tried to add this professor : ".$profModel->resolveProfessorOperationError(),
                "type" => "danger"
            ];
        }
    }
}



$content = $form->professorFormView($errors, $info);

$dashboard = new DashBoard();
$dashboard->view("Admin", "newProfessor", $content);

?>