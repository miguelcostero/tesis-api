<?php

require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->isSMTP();
$mail->Host = 'mail.talentproducciones.com.ve';
$mail->SMTPAuth = true;
$mail->Username = 'no-reply@talentproducciones.com.ve';
$mail->Password = 'T]@L2M1u;?k(';
$mail->SMTPSecure = 'tls';
$mail->Port = 465;