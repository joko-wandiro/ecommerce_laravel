<?php

namespace App\Libraries\Email;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{

    protected $host = 'smtp.gmail.com';
    protected $username = 'joko.wandiros@gmail.com';
    protected $password = 'J4V4source';
    protected $port = 587;
    protected $from_address = 'admin@demo.com';
    protected $from_name = 'Mailer';

    /**
     * Constructor
     * Set data for menu
     * Group data using parent ID
     * Set children records of specific parent
     * 
     * @param array $records
     * @return \App\Libraries\Menu\Menu
     */
    public function __construct()
    {
        
    }

    public function send($address, $name, $subject, $content)
    {
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 0;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host = $this->host;                    // Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = $this->username;                     // SMTP username
            $mail->Password = $this->password;                               // SMTP password
            $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port = $this->port;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            // Recipients
            $mail->setFrom($this->from_address, $this->from_name);
            $mail->addAddress($address, $name);     // Add a recipient
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $content;
            $mail->send();
            return TRUE;
        } catch (Exception $e) {
            return FALSE;
        }
    }

}
