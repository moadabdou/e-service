<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require_once __DIR__.'/../../vendor/autoload.php';

class  eMail{
    private  PHPMailer $mailer;

    public function __construct()

    {
        $dotenv = Dotenv::createImmutable(__DIR__."/../..");
        $dotenv->load();

        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host       = 'smtp.gmail.com'; 
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = $_ENV["service_email"]; 
        $this->mailer->Password   = $_ENV["password_email"]; 
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port       = 587;
        $this->mailer->setFrom($_ENV["service_email"] , 'ENSAH E-Service');

    } 

    public function sendTo(string $receiver, string $subject, string $body, bool $isHTML = true): string | true
    {

        try {

            $this->mailer->addAddress($receiver); 
            $this->mailer->isHTML($isHTML);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;
            $this->mailer->send();

            return true;

        } catch (Exception $e) {

            return $this->mailer->ErrorInfo;

        }
    }


}



?>

