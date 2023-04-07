<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'config.php';
session_start();
$msg = "";

if (isset($_GET['verification'])) {
    $verificationCode = mysqli_real_escape_string($conn, $_GET['verification']);
    $result = mysqli_query($conn, "SELECT * FROM users WHERE code='{$verificationCode}'");
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $isVerified = $row['is_verified'];

        if ($isVerified == "1") {
            header("Location: dashboard.php");
        } else {
            $query = mysqli_query($conn, "UPDATE users SET code='',is_verified='1' WHERE code='{$verificationCode}'");

            if ($query) {
                if (isset($_SESSION['SESSION_LOGGED_IN'])) {
                    header("Location: dashboard.php");
                    die();
                }
                $msg = "<div class='alert alert-success'>Your account has been verified</div>";
            }
        }
    }
} else {
    if (isset($_SESSION['SESSION_LOGGED_IN'])) {
        header("Location: dashboard.php");
        die();
    }
}

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $salt = "D;%yL9TS:5PalS/d";
    $hashedPassword = hash('sha256', $password . $salt);

    $sk = $_POST['g-recaptcha-response'];
    $site_key = "6LdR5S4lAAAAAMMink7zrczxd9qituO_2els-qMs";
    $ip = $_SERVER['REMOTE_ADDR'];
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$site_key&response=$sk&remoteip=$ip";
    $fire = file_get_contents($url);
    $data = json_decode($fire, true);

    if ($data['success'] == "true") {
        $otp = mt_rand(100000, 999999);

        $sql = "SELECT * FROM users WHERE email='{$email}' AND password='{$hashedPassword}'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['SESSION_ID'] = $row['id'];
            $_SESSION['SESSION_EMAIL'] = $email;

            $password_last_updated = $row['password_last_updated'];
            $interval_in_days = floor((strtotime('now') - strtotime($password_last_updated)) / (60 * 60 * 24));
            // $interval_in_minute = floor((strtotime('now') - strtotime($password_last_updated)) / (60 * 60));

            if ($interval_in_days > 45) {
                $code = mysqli_real_escape_string($conn, md5(rand()));
                $query = mysqli_query($conn, "UPDATE users SET code='{$code}' WHERE email='{$email}'");

                if ($query) {
                    $mail = new PHPMailer(false);

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
                }
                $msg = "<div class='alert alert-danger'>Your password has expired<br/>Check your email to update your password</div>";
            } else {
                $sql = "UPDATE users SET otp='{$otp}'";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $mail = new PHPMailer(true);

                    try {
                        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
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
                        $mail->Body = "<h1 style='color:#4070f4;'>Secure Auth</h1><p>Use this OTP to login to our application</p><b>$otp<b>";

                        $mail->send();

                        $_SESSION['SESSION_PASSWD'] = $password;
                        header("Location: otp.php");
                    } catch (Exception $e) {
                        echo "Couldn't send message";
                    }
                } else {
                    $msg = "<div class='alert alert-danger'>Something went wrong</div>";
                }
            }
        } else {
            $msg = "<div class='alert alert-danger'>Invalid email or password</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>Verification Needed<p>Please verify you are not a robot</p></div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>SecureAuth | Login</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/styles.css" type="text/css" media="all" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>

<body>
    <div class="container">
        <div class="forms">
            <div class="form login">
                <span class="title">Login</span>
                <?php echo $msg; ?>

                <form action="" method="post">
                    <div class="input-field">
                        <input type="email" class="email" name="email" placeholder="Enter your email" value="<?php if (isset($_POST['submit'])) {
                            echo $email;
                        } ?>" required>
                        <i class="uil uil-envelope icon"></i>
                    </div>
                    <div class="input-field">
                        <input id="psw-input" type="password" class="form-control password" name="password"
                            placeholder="Enter your password" required>
                        <i class="uil uil-lock icon"></i>
                        <i class="uil uil-eye-slash showHidePw"></i>
                    </div>
                    <div id="pswmeter" class="mt-3"></div>
                    <div id="pswmeter-message" class="mt-3"></div>

                    <div class="forgot-text">
                        <a href="forgot-password.php" class="text">Forgot password?</a>
                    </div>

                    <div class="form-group" style="margin-top: 16px;">
                        <div style="display:flex;justify-content:center;" class="g-recaptcha"
                            data-sitekey="6LdR5S4lAAAAADzc7VvH4-LJ6L4uNpT4P8YQZ2MK">
                        </div>

                        <div class="input-field button">
                            <button name="submit" name="submit" style="width: 100%; height: 50px"
                                type="submit">Login</button>
                        </div>
                    </div>
                </form>
                <div class="login-signup">
                    <span class="text">Not a member? <a href="register.php">Register</a>.</span>
                </div>
            </div>
        </div>
    </div>
    <script src="js/script.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>

</html>