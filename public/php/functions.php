<?php
require __DIR__ .'/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//point it to where .env exists
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();
function sanitize($input) {
    if (!is_string($input)) return $input;

    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
function random_num($length){
	$text ="";
	if ($length<5) {
		$length=5;
	}
	$len=rand(4,$length);
	for ($i=0; $i < $len ; $i++) { 
		$text .= rand(0,9);
	}
	return $text;
}
function convertToWebP($input, $output, $quality = 80) {
    $ext = strtolower(pathinfo($input, PATHINFO_EXTENSION));
    $image = null;

    switch ($ext) {
        case 'jpg':
        case 'jpeg':
        case 'jfif':
            $image = imagecreatefromjpeg($input);
            break;
        case 'png':
            $image = imagecreatefrompng($input);
            break;
        case 'gif':
            $image = imagecreatefromgif($input);
            break;
        default:
            return false; // Unsupported format
    }

    if (!$image) {
        return false;
    }

    // Create WebP file
    $result = imagewebp($image, $output, $quality);
    imagedestroy($image);
    return $result;
}
function sendEmail($email,$token,$otp){

$mail = new PHPMailer(true);
        // Create reset link
    $resetLink = "http://localhost:8000/project/family-tree/public/php/otpVerification.php?token=".$token;

    try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['SMTP_email']; // must be full Gmail address
    $mail->Password   = $_ENV['SMTP_PASSWORD'];       // Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('wavetonsolutions@gmail.com', 'Waveton Solutions');
    $mail->addAddress($email, 'Client Name'); 

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Password Reset Request';
    $mail->Body    = "
                    <h2>Password Reset</h2>
                    <p>Hello,</p>
                    <p>Click the link below to reset your password. This link is valid for <b>1 hour</b>.</p>
                    <p><a href='$resetLink'>resetLink</a></p>
                    <p>your OTP is: $otp</p>
                    <p>If you did not request a reset, ignore this email.</p>
                ";
                $mail->AltBody = "Password reset link: $resetLink";

    $mail->send();
    //echo json_encode(["success" => true, "message" => "✅ Email has been sent "]);
    return;
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
    return;
}
}
?>