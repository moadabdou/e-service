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
        $subject = "Welcome Aboard! Your Account is Ready";
        $body = "Dear ".$name.",<br><br>We're excited to inform you that your account has been successfully set up.<br><br>You can now access your account using the credentials below<br><br>Password: ".$password."<br><br>To log in, visit: ".$login_url."<br><br>For security reasons, we highly recommend changing your password upon first login.<br><br>If you have any questions, feel free to reach out.<br><br>Best regards,<br>ENSAH <br>Support Team<br>";
        return $this->email_handler->sendTo($email, $subject, $body);
    }


}

?>
