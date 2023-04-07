<?php
session_start();
if (isset($_SESSION['SESSION_LOGGED_IN'])) {
    header("Location: dashboard.php");
    die();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

include 'config.php';
$msg = "";

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $code = mysqli_real_escape_string($conn, md5(rand()));
    $_SESSION['SESSION_EMAIL'] = $email;

    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email='{$email}'")) > 0) {
        $query = mysqli_query($conn, "UPDATE users SET code='{$code}' WHERE email='{$email}'");

        if ($query) {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'secureauth315@gmail.com';
                $mail->Password = 'iptvrxkdxteayegf';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                $mail->setFrom('secureauth315@gmail.com');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'no reply';
                $mail->Body = '<h1 style="color:#4070f4;">Secure Auth</h1><p>Change the password for your account using the link below</p><b><a href="http://localhost/secure-auth/change-password.php?reset=' . $code . '">http://localhost/secure-auth/change-password.php?reset=' . $code . '</a></b>';

                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            echo "</div>";
            $msg = "<div class='alert alert-info'>We've sent a link to change your password on your email address.</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>$email - This email address do not found.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>SecureAuth | Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />

    <link rel="stylesheet" href="css/styles.css" type="text/css" media="all" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>

<body>
    <div class="container">
        <div class="forms">
            <div class="form login">
                <span class="title">Forgot Password</span>
                <?php echo $msg; ?>

                <form action="" method="post">
                    <div class="input-field">
                        <input type="email" class="email" name="email" placeholder="Enter your Email" required>
                        <i class="uil uil-envelope icon"></i>
                    </div>
                    <div class="input-field button">
                        <button name="submit" name="submit" style="width: 100%; height: 50px"
                            type="submit">Submit</button>
                    </div>
                </form>

                <div class="login-signup">
                    <span class="text">Go to <a href="index.php">Login</a>.</span>
                </div>
            </div>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>

</html>