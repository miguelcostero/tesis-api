<?php

function sendEmail ($email) {
  require __DIR__ . '/../vendor/autoload.php';

  $mail = new PHPMailer;

  // $mail->SMTPDebug = 0;
  $mail->isSMTP();
  $mail->CharSet = 'UTF-8';
  $mail->SMTPAuth = true;

  $mail->Host = 'mail.talentproducciones.com.ve';
  // $mail->Port = 465;
  $mail->Port = 25;
  $mail->Username = 'no-reply@talentproducciones.com.ve';
  $mail->Password = 'T]@L2M1u;?k(';
  // $mail->SMTPSecure = 'tls';

  $mail->setFrom('no-reply@talentproducciones.com.ve', 'no-reply@talentproducciones.com.ve');
  $mail->isHTML(true);
  $mail->addAddress($email->address->email, $email->address->name);
  $mail->Subject = $email->subject;
  $mail->Body = $email->body;
  $mail->AltBody = $email->altbody;

  if (!$mail->send()) {
    return (object) array('status' => 'failed', 'message' => $mail->ErrorInfo);
  } else {
    return (object) array('status' => 'success', 'message' => 'Mensaje Enviado');
  }
}