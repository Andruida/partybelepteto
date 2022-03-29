<?php
require_once($_SERVER["DOCUMENT_ROOT"] .'/classloader.php');

use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception as phpmailerException;

/**
 * Class Mailer - Uses PHPMailer plugin, SMTP capable
 */
class Mailer{

    private $MailerEngine;

    public function __construct($To, $Subject){
        try {

            $this -> MailerEngine = new PHPMailer(true);
            $this -> MailerEngine -> isHTML(true);
            $this -> MailerEngine -> CharSet = "UTF-8";

            $options = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/config/config.ini', true);
            $smtpOptions = $options["smtp"];

            $this -> MailerEngine -> setFrom($smtpOptions["address"], $smtpOptions["name"]);

            $this -> MailerEngine -> addAddress($To);

            $this -> MailerEngine -> isSMTP();
            $this -> MailerEngine -> SMTPDebug = 0;
            $this -> MailerEngine -> SMTPAuth = true;
            
            $this -> MailerEngine -> Host = $smtpOptions['host'];
            $this -> MailerEngine -> Port = $smtpOptions['port'];
            $this -> MailerEngine -> SMTPSecure = $smtpOptions['tls'];
            $this -> MailerEngine -> Username = $smtpOptions['user'];                // SMTP username
            $this -> MailerEngine -> Password = $smtpOptions['pw'];
            $this -> MailerEngine -> SMTPOptions = array(
                'ssl' => array( 
                    // NOTE: May be insecure, but not a vulnerability
                    //       This communication should happend through LAN anyways
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => false
                )
            );
            

            if(!empty($BCC))
                $this -> MailerEngine -> addBCC($BCC);
            $this -> MailerEngine -> Subject = $Subject;
        }catch(phpmailerException $e){
            die("A levelezőrendszer indítása sikertelen volt, kérem próbálja meg később!");
        }
    }

    public function send($Body){
       $this -> MailerEngine -> Body = $Body;
       try{
            if ($this -> MailerEngine -> send()) {
                return true;
            } else {
                return false;
            }
       }catch(phpmailerException $e){
           return false;
       }
    }

    public static function mail($To, $Subject, $Body, $filename=null) {
        $mailer = new self($To, $Subject);
        if ($filename !== null && file_exists($filename)) {
            $mailer->MailerEngine->addAttachment($filename, "qrcode.png");
        }
        return $mailer->send($Body);
    }
}