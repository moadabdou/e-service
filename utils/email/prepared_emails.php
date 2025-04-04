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
        $body = "Dear ".$name.",\n\nWe're excited to inform you that your account has been successfully set up.\n\nYou can now access your account using the credentials below:\n\nPassword: ".$password."\n\nTo log in, visit: ".$login_url."\n\nFor security reasons, we highly recommend changing your password upon first login.\n\nIf you have any questions, feel free to reach out.\n\nBest regards,\nENSAH \nSupport Team\n";
        return $this->email_handler->sendTo($email, $subject, $body);
    }


}

?>