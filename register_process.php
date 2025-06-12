<?php
// Start the session
session_start();

// Include PHPMailer classes
require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the email from the form
    $email = $_POST['email'];

    // Validate the email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address. Please provide a valid email.";
        exit;
    }

    // Generate a random 4-digit OTP
    $otp = rand(1000, 9999);

    // Store the OTP in the session
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                                       // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                  // Set the SMTP server to Gmail
        $mail->SMTPAuth   = true;                              // Enable SMTP authentication
        $mail->Username   = 'your_email';            // Your Gmail address
        $mail->Password   = 'APP_password';               // Your Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;    // Enable TLS encryption
        $mail->Port       = 587;                               // TCP port for Gmail

        // Recipients
        $mail->setFrom('your-email@gmail.com', 'Your Website');
        $mail->addAddress($email); // Send OTP to the user's email

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Hello, your OTP code is <strong>$otp</strong>. Please use this code to complete your registration.";

        // Send the email
        $mail->send();
        echo "OTP sent successfully! Please check your email.";
    } catch (Exception $e) {
        echo "OTP could not be sent. Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Invalid request. Please submit the form.";
}
?>
