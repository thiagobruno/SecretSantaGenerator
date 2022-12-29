<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * TIMEZONE
 */
$timezone = 'UTC'; // instead of UTC you can set a timezone like: "America/Edmonton"

/**
 * DATABASE
 */
$servername = "db"; // Docker container address, you also can use an IP if you host the database in another server
$username = "hugo";
$password = "hugo";
$dbname = "secret_santa_db";
$dbport = 3306;

/**
 * EMAIL - SENDER
 */
$mail_from = 'your@email.com';
$mail_title = 'Secret Santa';

/**
 * SMTP
 */
// Create an account on Mailttrap.io to get your username and password
// ***Also, you can use the normal SMTP instead of using the Mailtrap above, Just replace the variables with your SMTP data
// https://mailtrap.io
$smtp_host = 'smtp.mailtrap.io';
$smtp_port = 2525;
$smtp_username = '78453f9791b194';
$smtp_password = 'da8397c860cf66';

/**
 * CURRENT DATE TIME
 */
$date = new DateTimeImmutable();

/**
 * INITIALIZE VENDOR LIBRARIES
 */
require_once('vendor/autoload.php');