<?php
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/layouts/user_register_forms/forms.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/entity/professor.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/utils/email/prepared_emails.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/content/notification.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/content/activity.php";
$activityModel = new ActivityModel();

session_start();

$userController =  new UserController();

$userController->checkCurrentUserAuthority(["professor/coordonnateur"]);


$form = new Form();
$notificationModel = new NotificationModel();


$errors = [];
$info = null;

if($_SERVER["REQUEST_METHOD"] ==  "POST"){

    if (empty($_POST['firstName']) || !$userController->isValidUserData('firstName', $_POST['firstName'])) {
        $errors["firstName"] = "Le prénom est requis et ne doit contenir que des lettres, espaces, ' et -";
    }

    if (empty($_POST['lastName']) || !$userController->isValidUserData('lastName', $_POST['lastName'])) {
        $errors["lastName"] = "Le nom est requis et ne doit contenir que des lettres, espaces, ' et -";
    }

    if (empty($_POST['email']) || !$userController->isValidUserData('email', $_POST['email'])) {
        $errors["email"] = "Une adresse email valide est requise";
    }

    if (empty($_POST['phone']) || !$userController->isValidUserData('phone', $_POST['phone'])) {
        $errors["phone"] = "Le numéro de téléphone est requis et doit contenir 10 chiffres";
    }

    if (empty($_POST['address']) || !$userController->isValidUserData('address', $_POST['address'])) {
        $errors["address"] = "Une adresse valide est requise";
    }

    if (empty($_POST['CIN']) || !$userController->isValidUserData('CIN', $_POST['CIN'])) {
        $errors["cin"] = "La CIN est requise et doit contenir 7 caractères en majuscules et chiffres (ex: R123456)";
    }

    if (empty($_POST['birth_date']) || !$userController->isValidUserData('birth_date', $_POST['birth_date'])) {
        $errors["birth_date"] = "Une date de naissance valide est requise (plus de 24 ans)";
    }

    if (empty($_POST['id_speciality']) || !$userController->isValidUserData('id_speciality', $_POST['id_speciality'])) {
        $errors["id_speciality"] = "La spécialité est requise et doit être un ID valide";
    }

    if (empty($_POST['max_hours']) || !$userController->isValidUserData('max_hours', $_POST['max_hours'])) {
        $errors["max_hours"] = "Le nombre maximum d'heures doit être un nombre positif";
    }

    if (empty($_POST['min_hours']) || !$userController->isValidUserData('min_hours', $_POST['min_hours'])) {
        $errors["min_hours"] = "Le nombre minimum d'heures doit être un nombre positif";
    }

    if (count($errors)){
        $info =  [
            "msg" => "Nous avons trouvé un problème avec le format des données lors de l'ajout de ce professeur",
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
            $_POST['id_speciality'],
            $_POST['max_hours'],
            $_POST['min_hours']
        );

        if ($res === false){
            $info =  [
                "msg" => "Le format des données semble correct mais une erreur s'est produite lors de l'ajout de ce professeur : ".$profModel->resolveProfessorOperationError(),
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

            $activityModel->createActivity(
                "Un nouveau professeur " . $_POST["firstName"] . " " . $_POST["lastName"] . " a été ajouté",
                "user-plus"
            );

            //professor is ready, sending the email 

            $emails = new PreparedEmails();

            $email_sent = $emails->accountIsReadyEmail($_POST["email"], $new_prof_password, $_POST["firstName"]." ".$_POST["lastName"]);

            if ($email_sent !== true){
                $info =  [
                    "msg" => "L'inscription a réussi, mais nous n'avons pas pu envoyer l'email au professeur {".htmlspecialchars($new_prof_password)."} ".$email_sent,
                    "type" => "warning"
                ]; 
            }else {
                
                $info =  [
                    "msg" => "L'inscription a réussi, un email a été envoyé au professeur",
                    "type" => "success"
                ]; 

            }

        }
    }
}


$content = $form->vacataireFormView($errors, $info);

$dashboard = new DashBoard();
$dashboard->view("professor/coordonnateur", "addVacataire", $content);

?>