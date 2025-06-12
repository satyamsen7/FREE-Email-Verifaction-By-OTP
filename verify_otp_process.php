<?php
session_start();
require 'config.php'; // Include DB connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp = htmlspecialchars($_POST['otp']);
    $email = $_SESSION['email'];

    // Check OTP
    $stmt = $conn->prepare("SELECT otp FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($db_otp);
    $stmt->fetch();
    $stmt->close();

    if ($otp == $db_otp) {
        // Update user as verified
        $stmt = $conn->prepare("UPDATE users SET is_verified = 1, otp = NULL WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        echo "Account verified successfully!";
    } else {
        echo "Invalid OTP. Try again.";
    }
}
?>
