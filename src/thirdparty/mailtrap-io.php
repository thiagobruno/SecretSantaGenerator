<?php
// Set the PHPMailer library, to use the SMTP 
$phpmailer = new PHPMailer\PHPMailer\PHPMailer();
$phpmailer->isSMTP();
$phpmailer->Host = $smtp_host;
$phpmailer->SMTPAuth = true;
$phpmailer->Port = $smtp_port;
$phpmailer->Username = $smtp_username;
$phpmailer->Password = $smtp_password;