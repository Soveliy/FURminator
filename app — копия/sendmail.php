

<?php

/**
 * PHPMailer multiple files upload and send example
 */

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;

$msg = '';
if (array_key_exists('check-file', $_FILES)) {

    // Create a message
    $mail = new PHPMailer();
    $mail->setFrom('nikita.elagin2012@yandex.ru', 'First Last');
    $mail->addAddress('nikita.elagin2012@yandex.ru', 'John Doe');
    $mail->Subject = 'PHPMailer file sender';
    $mail->Body = 'My message body';
    //Attach multiple files one by one
    for ($ct = 0, $ctMax = count($_FILES['check-file']['tmp_name']); $ct < $ctMax; $ct++) {
        $uploadfile = tempnam(sys_get_temp_dir(), hash('sha256', $_FILES['check-file']['name'][$ct]));
        $filename = $_FILES['check-file']['name'][$ct];
        if (move_uploaded_file($_FILES['check-file']['tmp_name'][$ct], $uploadfile)) {
            if (!$mail->addAttachment($uploadfile, $filename)) {
                $msg .= 'Failed to attach file ' . $filename;
            }
        } else {
            $msg .= 'Failed to move file to ' . $uploadfile;
        }
    }
    if (!$mail->send()) {
        $msg .= 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        $msg .= 'Message sent!';
    }
}
?>