<?php 
require_once __DIR__."/email.php";

class PreparedEmails{

    private eMail  $email_handler;

    public function __construct()
    {
        $this->email_handler = new eMail();
    }

    public function accountIsReadyEmail(string $email, string $password, string $name): string | true{
        $login_url = "http://127.0.0.1/e-service/public/login.php";
        $subject = "Bienvenue ! Votre compte est prêt";
        $body = "Cher(e) ".$name.",<br><br>Nous sommes ravis de vous informer que votre compte a été créé avec succès.<br><br>Vous pouvez maintenant accéder à votre compte en utilisant les identifiants ci-dessous<br><br>Mot de passe : ".$password."<br><br>Pour vous connecter, visitez : ".$login_url."<br><br>Pour des raisons de sécurité, nous vous recommandons vivement de modifier votre mot de passe lors de votre première connexion.<br><br>Si vous avez des questions, n'hésitez pas à nous contacter.<br><br>Cordialement,<br>ENSAH<br>L'équipe support<br>";
        return $this->email_handler->sendTo($email, $subject, $body);
    }
    public function passwordResetEmail(string $email, string $password, string $name): string | true {
        $login_url = "http://127.0.0.1/e-service/public/login.php";
        $subject = "Confirmation de réinitialisation du mot de passe";
        $body = "Cher(e) ".$name.",<br><br>Votre mot de passe a été réinitialisé avec succès.<br><br>Votre nouveau mot de passe est : ".$password."<br><br>Pour vous connecter, visitez : ".$login_url."<br><br>Pour des raisons de sécurité, nous vous recommandons vivement de modifier votre mot de passe lors de votre première connexion.<br><br>Si vous n'avez pas demandé cette réinitialisation de mot de passe, veuillez nous contacter immédiatement.<br><br>Cordialement,<br>ENSAH<br>L'équipe support<br>";
        return $this->email_handler->sendTo($email, $subject, $body);
    }


}

?>
