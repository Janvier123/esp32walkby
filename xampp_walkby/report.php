<?php
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include_once 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$query = "SELECT * FROM walks WHERE action > '".strtotime("yesterday")."'  AND action < '".strtotime("today")."' ORDER BY id ASC";
$result = $conn_sql->query($query);
$num_rows = mysqli_num_rows($result);

// want madam wilt ni dat haar dikke reet ook mee telt
if($num_rows > 2)
{
	$num_rows = $num_rows - 2;
}
else
{
	$num_rows = 0;
}

$tijd_eenmalig = 8; //sec
$tredes = 16;
$cal_pre_trede = 0.17;


$tredes_yesterday = $num_rows * $tredes;
$time_on_stairs = $num_rows * $tijd_eenmalig;

$totaal_tredes = $num_rows * $tredes;
$totaal_cal = $totaal_tredes * $cal_pre_trede;
$totaal_kcal = $totaal_cal;

function format_seconds($time)
{
	$minutes = floor($time / 60);
	$time -= $minutes * 60;
	$seconds = floor($time);
	$time -= $seconds;
	return "{$minutes} min, {$seconds} sec";
}

function round_up_down($time)
{
	$minutes = floor($time / 60);
	$time -= $minutes * 60;
	$seconds = floor($time);
	$time -= $seconds;
	if($seconds > 30)
	{
		return $minutes + 1;
	}
	else if ($seconds == 30)
	{
		return $minutes + 1;
	}
	else
	{
		return $minutes;
	}
}

function render_email($value1, $value2, $value3, $value4)
{
    ob_start();
    include "email.phtml";
    return ob_get_contents();
}

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.server.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'Username';                     //SMTP username
    $mail->Password   = 'Password';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('Username@server.com', 'Username');
    $mail->addAddress('Username@server.com', 'Username');
	$mail->addAddress('Username@server.com', 'Username');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
	
    $mail->Subject = 'Statistieken traplopen ' . date("d/m", strtotime("yesterday"));
	
	$mail->Body = render_email
	(
		number_format($tredes_yesterday, 0, '', '.'), 
		format_seconds($time_on_stairs), 
		round($totaal_kcal,0,PHP_ROUND_HALF_UP),
		$num_rows,
	);	


    $mail->send();
    echo 'Message has been sent';
}
catch (Exception $e)
{
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>