<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class send_email {
    private $mail;
    private $config;
    private $error;

    private $name;
    private $email;
    private $subject;
    private $text;

    public function __construct($name, $email, $subject, $text) {
        $this->name = $name;
        $this->email = $email;
        $this->subject = $subject;
        $this->text = $text;

        header('Content-type: text/html; charset=utf-8');

        require __DIR__ . '/PHPMailer/src/Exception.php';
        require __DIR__ . '/PHPMailer/src/PHPMailer.php';
        require __DIR__ . '/PHPMailer/src/SMTP.php';

        $this->config = include __DIR__ . '/../config.php';
        $this->config = $this->config["email"];

        date_default_timezone_set('Europe/Warsaw');
        $this->mail = new PHPMailer(true);
    }
    public function send() {
        try {
            $this->mail->isSMTP();
            // $this->mail->SMTPDebug = 4;
            $this->mail->Host = $this->config["email_host"];
            $this->mail->SMTPAuth = $this->config["email_auth"];
            $this->mail->Username = $this->config["email_user"];
            $this->mail->Password = $this->config["email_password"];
            if($this->config["email_secure"] != "none") {
                $this->mail->SMTPSecure = $this->config["email_secure"];
            }
            $this->mail->Port = $this->config["email_port"];
            $this->mail->SMTPAutoTLS = false;
            $this->mail->CharSet = "UTF-8";
            $this->mail->setLanguage('pl', __DIR__ . '/PHPMailer/language');
    
            $this->mail->setFrom($this->config["email_user"], 'RA WORK');
            $this->mail->addAddress($this->email, $this->name);
            //$mail->addReplyTo($email, $imie);
    
            $this->mail->isHTML(true);
            $this->mail->Subject = $this->subject;
            $this->mail->Body = $this->text;
            $this->mail->AltBody = 'By wyświetlić wiadomość należy skorzystać z czytnika obsługującego wiadomości w formie HTML';
    
            $this->mail->send();
            return true;
    
        } catch (Exception $e) {
            $this->error = $e;
            return false;
        }
    }
    public function getEmailError() {
        return $this->error;
    }
}
