<?php
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/layouts/user_register_forms/forms.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/entity/professor.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/utils/email/prepared_emails.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/content/notification.php";


session_start();

$userController =  new UserController();

$userController->checkCurrentUserAuthority(["admin"]);



$form = new Form();
$notificationModel = new NotificationModel();


$errors = [];
$info = null;

if($_SERVER["REQUEST_METHOD"] ==  "POST"){
    //professor is ready, sending the email 

    if (empty($_POST['firstName']) || !$userController->isValidUserData('firstName', $_POST['firstName'])) {
        $errors["firstName"] = "First name is required and should contain only letters , spaces,',-";
    }

    if (empty($_POST['lastName']) || !$userController->isValidUserData('lastName', $_POST['lastName'])) {
        $errors["lastName"] = "Last name is required and should contain only letters , spaces,',-";
    }

    if (empty($_POST['email']) || !$userController->isValidUserData('email', $_POST['email'])) {
        $errors["email"] = "A valid email address is required";
    }

    if (empty($_POST['phone']) || !$userController->isValidUserData('phone', $_POST['phone'])) {
        $errors["phone"] = "Phone number is required and should be 10 digits";
    }

    if (empty($_POST['address']) || !$userController->isValidUserData('address', $_POST['address'])) {
        $errors["address"] = "A valid Address is required";
    }

    if (empty($_POST['CIN']) || !$userController->isValidUserData('CIN', $_POST['CIN'])) {
        $errors["cin"] = "CIN is required and should be 7 characters of uppercase letters and numbers (e.g R123456)";
    }

    if (empty($_POST['birth_date']) || !$userController->isValidUserData('birth_date', $_POST['birth_date'])) {
        $errors["birth_date"] = "A valid birth date is required (older than 24 yrs old)";
    }

    if (empty($_POST['id_deparetement']) || !$userController->isValidUserData('id_deparetement', $_POST['id_deparetement'])) {
        $errors["id_deparetement"] = "Department is required and must be a valid ID";
    }

    if (empty($_POST['max_hours']) || !$userController->isValidUserData('max_hours', $_POST['max_hours'])) {
        $errors["max_hours"] = "Maximum work hours must be a positive number";
    }

    if (empty($_POST['min_hours']) || !$userController->isValidUserData('min_hours', $_POST['min_hours'])) {
        $errors["min_hours"] = "Minimum work hours must be a positive number";
    }

    if (count($errors)){
        $info =  [
            "msg" => "we found a problem with the data format when we tried to add this professor",
            "type" => "danger"
        ];
    }else {
        $profModel = new ProfessorModel();

        $res = $profModel->newProfessor(
            $_POST['firstName'],
            $_POST['lastName'],
            $_POST['CIN'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['address'],
            $_POST['birth_date'],
            $_POST['id_deparetement'],
            $_POST['max_hours'],
            $_POST['min_hours']
        );

        if ($res === false){
            $info =  [
                "msg" => "data format looks fine but an error accured when we tried to add this professor : ".$profModel->resolveProfessorOperationError(),
                "type" => "danger"
            ];
        }else {

            [$prof_id , $new_prof_password] = $res;
            $notificationModel->createNotification(
                $prof_id, 
                "Bienvenue sur E-service", 
                "Veuillez changer votre mot de passe temporaire dès que possible pour la sécurité de votre compte. Vous pouvez le faire en allant dans les paramètres de votre profil.",
                null
            );

            //professor is ready, sending the email 

            $emails = new PreparedEmails();

            $email_sent = $emails->accountIsReadyEmail($_POST["email"], $new_prof_password, $_POST["firstName"]." ".$_POST["lastName"]);

            if ($email_sent !== true){
                $info =  [
                    "msg" => "the  registration was seccussfull,  but we were not able to send the email to the professor {".htmlspecialchars($new_prof_password)."} ".$email_sent,
                    "type" => "warning"
                ]; 
            }else {
                
                $info =  [
                    "msg" => "the  registration was seccussfull,  an email  is sent to the professor",
                    "type" => "success"
                ]; 

            }

        }
    }
}


$content = $form->professorFormView($errors, $info);

$dashboard = new DashBoard();
$dashboard->view("admin", "newProfessor", $content);

?>