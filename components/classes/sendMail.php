<?php
namespace components\classes;

use components\classes\PHPMailer\PHPMailer;

class sendMail
{
    private $tmpl;
    
    public $mail;
    
    const TEMPLATE_PATH = PATH . "/components/template/";
    
    public function __construct() {
        $this->mail = new PHPMailer(true);
        
        // $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $this->mail->isSMTP();                                            //Send using SMTP
        $this->mail->Host       = 'smtp.ionos.de';                     //Set the SMTP server to send through
        $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $this->mail->Username   = 'manuel@sagorski.net';                     //SMTP username
        $this->mail->Password   = 'it10sagoma';                               //SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $this->mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $this->mail->CharSet   = 'UTF-8';
        $this->mail->isHTML(true);
        $this->mail->setFrom('manuel@sagorski.net', 'Hafendienst BO');
    }
    
    public function applyTemplate($templateName, $replace) {
        $this->tmpl = file_get_contents(self::TEMPLATE_PATH . $templateName . ".html");
        
        foreach ($replace as $key=>$value) {
            $this->tmpl = str_replace("{{" . $key . "}}", $value, $this->tmpl);
        }
        
        $this->mail->Body = $this->tmpl;
    }
}

?>