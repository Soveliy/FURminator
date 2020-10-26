<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

# https://www.php.net/manual/fr/timezones.php
date_default_timezone_set('Europe/Moscow');

require __DIR__ . '/vendor/PHPMailer/Exception.php';
require __DIR__ . '/vendor/PHPMailer/PHPMailer.php';
require __DIR__ . '/vendor/PHPMailer/SMTP.php';
require __DIR__ . '/vendor/recaptcha/autoload.php';

class Ajax_Form {

    # Constants to redefined
    const HOST        = 'smtp.yandex.ru'; # SMTP server
    const USERNAME    = 'info@itotdel.pro'; # SMTP username
    const PASSWORD    = 'qV23E3ZZWee'; # SMTP password
    const SMTP_SECURE = PHPMailer::ENCRYPTION_STARTTLS;
    const SMTP_AUTH   = true;
    const PORT        = 587;
    const SECRET_KEY  = '6LeU7b0ZAAAAAAGNP3VX2G7O76bHDgr5EhxOihqJ'; # GOOGLE secret key
    const SUBJECT     = 'Заинтересовались? (itotdel.pro)';
    public $handler   = [
        'success'       => '✔️ Your message has been sent.',
        'token-error'   => '❌ Error recaptcha token.',
        'enter_name'    => '❌ Please enter your name.',
        'enter_phone'    => '❌ Please enter your phone.',
        'enter_email'   => '❌ Please enter a valid email.',
        'enter_message' => '❌ Please enter your message.',
        'ajax_only'     => '❌ Asynchronous anonymous.',
        'body'          => '
            <h1>{{subject}}</h1>
            <p><strong>Дата:</strong> {{date}}</p>
            <p><strong>Имя:</strong> {{name}}</p>
            <p><strong>Телефон:</strong> {{phone}}</p>
            <p><strong>E-Mail:</strong> {{email}}</p>
            <p><strong>Сообщение:</strong> {{message}}</p>
        ',
    ];

    /**
     * Ajax_Form __constructor
     */
    public function __construct() {

        # Check if request is Ajax request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 'XMLHttpRequest' !== $_SERVER['HTTP_X_REQUESTED_WITH']) {
            $this->statusHandler('ajax_only', 'error');
        }

        # Check if fields has been entered and valid
        # Get secure post data
        $name    = !empty($_GET['name']) ? filter_var($this->secure($_GET['name']), FILTER_SANITIZE_STRING) : $this->statusHandler('enter_name');
        $phone    = !empty($_GET['phone']) ? filter_var($this->secure($_GET['phone']), FILTER_SANITIZE_STRING) : $this->statusHandler('enter_phone');
        $email   = !empty($_GET['email']) ? filter_var($this->secure($_GET['email']), FILTER_SANITIZE_EMAIL) : $this->statusHandler('enter_email');
        $message = !empty($_GET['coment']) ? filter_var($this->secure($_GET['coment']), FILTER_SANITIZE_STRING) : $this->statusHandler('enter_message');
        $token   = !empty($_GET['recaptcha-token6']) ? filter_var($this->secure($_GET['recaptcha-token6']), FILTER_SANITIZE_STRING) : $this->statusHandler('token-error');
        $date    = new DateTime();

        # Prepare body
        $body = $this->getString('body');
        $body = $this->template( $body, [
            'subject' => self::SUBJECT,
            'date'    => $date->format('j/m/Y H:i:s'),
            'name'    => $name,
            'phone'    => $phone,
            'email'   => $email,
            'message' => $message,
        ] );

        # Verifying the user's response
        $recaptcha = new \ReCaptcha\ReCaptcha(self::SECRET_KEY);
        $resp = $recaptcha
            ->setExpectedHostname($_SERVER['SERVER_NAME'])
            ->verify($token, $_SERVER['REMOTE_ADDR']);

        if ($resp->isSuccess()) {

            $mail = new PHPMailer(true);
            $mail->setLanguage('ru', __DIR__ . '/vendor/PHPMailer/language/');

            try {
                # Server settings
                $mail->SMTPDebug  = SMTP::DEBUG_OFF;   # Enable verbose debug output
                $mail->isSMTP();                       # Set mailer to use SMTP
                $mail->Host       = self::HOST;        # Specify main and backup SMTP servers
                $mail->SMTPAuth   = self::SMTP_AUTH;   # Enable SMTP authentication
                $mail->Username   = self::USERNAME;    # SMTP username
                $mail->Password   = self::PASSWORD;    # SMTP password
                $mail->SMTPSecure = self::SMTP_SECURE; # Enable TLS encryption, `ssl` also accepted
                $mail->Port       = self::PORT;        # TCP port

                # Recipients
                // $mail->setFrom(self::USERNAME, 'info@itotdel.pro');
                // $mail->addAddress('zakaz@kurskhelp.ru'); // Email получателя
                //$mail->addAddress($email, $name);
                // $mail->AddCC(self::USERNAME, 'jenia1398@mail.ru');
                // $mail->addReplyTo(self::USERNAME, 'jenia1398@mail.ru');
                $mail->AddCC(self::USERNAME, 'nikita.elagin2012@yandex.ru');
                $mail->addReplyTo(self::USERNAME, 'nikita.elagin2012@yandex.ru');

                # Content
                $mail->CharSet = 'UTF-8';
                $mail->isHTML(true);
                $mail->Subject = self::SUBJECT;
                $mail->Body    = $body;
                $mail->AltBody = strip_tags($body);;

                $mail->send();
                $this->statusHandler('success');

            } catch (Exception $e) {
                die (json_encode( $mail->ErrorInfo ));
            }
        } else {
            die (json_encode( $resp->getErrorCodes() ));
        }
    }

    /**
     * Template string
     *
     * @param string $string
     * @param array $vars
     * @return string
     */
    public function template($string, $vars)
    {
        foreach ($vars as $name => $val) {
            $string = str_replace("{{{$name}}}", $val, $string);
        }
        return $string;
    }

    /**
     * Get string from $string variable
     *
     * @param string $string
     * @return string
     */
    public function getString($string)
    {
        return isset($this->handler[$string]) ? $this->handler[$string] : $string;
    }

    /**
     * Secure inputs fields
     *
     * @param string $post
     * @return string
     */
    public function secure($post)
    {
        $post = htmlspecialchars($post);
        $post = stripslashes($post);
        $post = trim($post);
        return $post;
    }

    public function statusHandler($message)
    {
        die (json_encode($this->getString($message)));
    }

}

# Instanciation
new Ajax_Form();