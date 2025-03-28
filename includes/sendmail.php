<?php
require __DIR__ . "/../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Envoie un mail de feedback à l'adresse mail de l'administrateur.
 *
 * @param array $user_details Les détails de l'utilisateur.
 * @return bool Indique si le mail a bien été envoyé.
 */
function send_feedback_mail(array $user_details): bool {
	require_once "load_environment.php";
	
	extract($user_details); //? $topic, $mail, $username, $message
	
	$date_time = new DateTime("now", new DateTimeZone("Europe/Paris"));
	$date = $date_time->format("d/m/Y");
	$time = $date_time->format("H:i");
	$mail = new PHPMailer(true);
	$mail->isSMTP();
	$mail->Host = $_ENV["SMTP_HOST"];
	$mail->SMTPAuth = true;
	$mail->Username = $_ENV["SMTP_USERNAME"];
	$mail->Password = $_ENV["SMTP_PASSWORD"];
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	$mail->Port = 587;
	$mail->setFrom("stardewvalley.dashboard@gmail.com", "Feedback Notification");
	$mail->addAddress("stardewvalley.dashboard@gmail.com");
	$mail->isHTML(false);
	$mail->Subject = "A new feedback just came in: $feedback_type";
	$mail->Body = "From: $email_adress - $date at $time" . PHP_EOL . "$message";
	
	return $mail->send();
}

header("Content-Type: application/json");

ob_start();
extract($_POST); //? $topic, $mail, $username, $message

$response = [
	"success" => false,
	"message" => "Error while sending the email. Please try again"
];

if($_SERVER["REQUEST_METHOD"] !== "POST") {
	echo json_encode($response);
	exit;
}

$user_details = [
	"feedback_type" => $topic,
	"email_adress" => $mail,
	"username" => $username,
	"message" => $message,
];

if(send_feedback_mail($user_details)) {
	$response["success"] = true;
	$response["message"] = "Your mail has been delivered successfully";
}

echo json_encode($response);
exit;
