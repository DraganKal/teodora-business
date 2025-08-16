<?php
/*
Name: 			Contact Form
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version:	12.0.0
*/

namespace PortoContactForm;

session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));

header('Content-type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'php-mailer/src/PHPMailer.php';
require 'php-mailer/src/SMTP.php';
require 'php-mailer/src/Exception.php';

// Step 1 - Enter your email address below.
$email = 'no-reply@teodorarankov.com';

// If the e-mail is not working, change the debug option to 2 | $debug = 2;
$debug = 0;

// If contact form don't has the subject input change the value of subject here
$mail = new PHPMailer(true);

$recaptcha_secret = '6LcfCu8qAAAAAO0aLmTGegoTImZbsT9mDaLkz6AP';
$recaptcha_response = $_POST['g-recaptcha-response'];

$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
$captcha_success = json_decode($verify);

if (!$captcha_success->success) {
    echo json_encode(['response' => 'error', 'errorMessage' => 'Captcha verification failed']);
    exit;
}

try {

	$mail->SMTPDebug = $debug;                                 // Debug Mode


  $program = $_POST['a-ili-n'];
  $name = $_POST['subject'];
  $email = $_POST['email'];
  $message = $_POST['message'];
  $naslov = $_POST['name'];
  $to = 'info@teodorarankov.com';
  $subject = "Prijava na $program";
  $headers = "From: no-reply@teodorarankov.com\r\n";
  $headers .= "Reply-To: $email\r\n";
  $headers .= "Content-type: text/plain; charset=UTF-8\r\n";

  // Send email to the website owner
  $fullMessage = "Telefon: $naslov\nIme i prezime: $name\nEmail: $email\nPoruka: $message\nProgram: $program";
  mail($to, $subject, $fullMessage, $headers);
  
	$arrResult = array ('response'=>'success');

} catch (Exception $e) {
	$arrResult = array ('response'=>'error','errorMessage'=>$e->errorMessage());
} catch (\Exception $e) {
	$arrResult = array ('response'=>'error','errorMessage'=>$e->getMessage());
}

if ($debug == 0) {
	echo json_encode($arrResult);
}