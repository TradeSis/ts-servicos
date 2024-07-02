<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader

include_once '../conexao.php';
require_once ROOT.'/vendor/autoload.php';


function emailEnviar($from,$fromNome,$arrayPara,$assunto,$corpo)
{
/* exemplo arrayPara
    $arrayPara    = array(
        array('email' => 'helio.alves@tradesis.com.br',
              'nome'  => 'Helio Alves'),
        array('email' => 'helio@tradesis.com.br',
              'nome'  => 'Helio 2'),

    );
*/
    $dadosEmail = defineEmail();
    if ($from == null) {
        $from = $dadosEmail['from'];
    }
    if ($fromNome == null) {
        $fromNome = $dadosEmail['fromNome'];
    }
    $Port = $dadosEmail['Port'];
    if ($Port == null) {
        $Port = 465;
    }

    if ($arrayPara == null) {
        return;
    }

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP(); //Send using SMTP
        $mail->Host =  $dadosEmail['Host'] ; //Set the SMTP server to send through
        $mail->SMTPAuth = true; //Enable SMTP authentication
        $mail->Username = $dadosEmail['Username']; //SMTP username
        $mail->Password = $dadosEmail['Password']; //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
        $mail->Port = $Port; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($from, $fromNome);

        foreach($arrayPara as $array) {
            $toEmail = $array["email"];
            $toNome = $array["nome"];
    
            if ($toNome==null) {
                $mail->addAddress($toEmail); //Add a recipient
            } else {
                $mail->addAddress($toEmail, $toNome); //Add a recipient
            }
            
        }
    
    

        //$mail->addAddress('helio.alvesneto67@gmail.com'); //Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true); //Set email format to HTML
        $mail->Subject = $assunto;
        $mail->Body = $corpo;
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        //echo 'Message has been sent';
    } catch (Exception $e) {
        return "Mailer Error: {$mail->ErrorInfo}";
    }

}