<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {
        //Server settings
        // crear el objeto de email 
        $mail = new PHPMailer(true);
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.mailtrap.io';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'b21a4120c2e36b';                     //SMTP username
        $mail->Password   = '027c68a73c641f';                               //SMTP password
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port = 2525;

        //Recipients
        $mail->setFrom('from@example.com');
        $mail->addAddress('joe@example.net');     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Confirma tu cuenta';
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        // .= sirve para concatenar la linea anterior
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta en App Salon, solo debes confirmarla
        presionando el siguiente enlace</p>";
        $contenido .= "<p>Preciona aquí: <a href='http://localhost:3000/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje.</p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;

        $mail->send();
        // echo 'Tu mensaje ha sido enviado';
    }
    public function enviarInstrucciones(){
        $mail = new PHPMailer(true);
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.mailtrap.io';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'b21a4120c2e36b';                     //SMTP username
        $mail->Password   = '027c68a73c641f';                               //SMTP password
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port = 2525;

        //Recipients
        $mail->setFrom('from@example.com');
        $mail->addAddress('joe@example.net');     //Add a recipient
        $mail->Subject = 'Reestablece tu password';
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Confirma tu cuenta';
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        // .= sirve para concatenar la linea anterior
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado reestablecer tu password, sigue este enlace para hacerlo.</p>";
        $contenido .= "<p>Preciona aquí: <a href='http://localhost:3000/recuperar?token=" . $this->token . "'>Reestablecer Cuenta</a></p>";
        $contenido .= "<p>si tu no solicitaste el cambio puedes ignorar el mensaje</p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje.</p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;

        $mail->send();
    }
}
