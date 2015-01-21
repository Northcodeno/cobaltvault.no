<?php

function sendMail($subject, $message, $toAddress, $toName, $rootDirectory = ".", $fromName = "Contact@Northcode")
{
	require_once($rootDirectory."/lib/PHPMailer/PHPMailerAutoload.php");
	$mail = new PHPMailer();

	$mail->IsSMTP();
	$mail->CharSet = "UTF-8";
	$mail->Host = "outgoing-smtp49s.stwadmin.net";
	$mail->SMTPAuth = true;
	$mail->Username = $mail->Username;
	$mail->Password = $mail->Password;
	$mail->SMTPDebug = 0;

	$mail->From = "contact@northcode.no";
	$mail->FromName = $fromName;
	$mail->addAddress($toAddress,$toName);
	$mail->addReplyTo("contact@northcode.no");

	$mail->isHtml(true);

	$mail->Subject = $subject;
	$mail->Body = $message;

	$mail->send();
}
