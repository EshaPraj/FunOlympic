<?php
use PHPMailer\PHPMailer\PHPMailer;
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
            header("Location: home");
        } else {
            $query = mysqli_query($conn, "UPDATE users SET code='',is_verified='1' WHERE code='{$verificationCode}'");

            if ($query) {
                if (isset($_SESSION['SESSION_LOGGED_IN'])) {
                    header("Location: home");
                    die();
                }
                $msg = "<div class='alert alert-success'>Your account has been verified</div>";
            }
        }
    }
} else {
    if (isset($_SESSION['SESSION_LOGGED_IN'])) {
        header("Location: home");
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
        $sql = "SELECT * FROM users WHERE email='{$email}' AND password='{$hashedPassword}'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['SESSION_ID'] = $row['id'];
            $_SESSION['SESSION_EMAIL'] = $email;
            $_SESSION['SESSION_ADMIN'] = $row['is_admin'] === "0" ? FALSE : TRUE;
            $cookie_name = "userName";
            $cookie_value = substr($email, 0, strpos($email, '@'));
            setcookie($cookie_name, $cookie_value, 0, "/"); // 86400 = 1 day

            $account_verified = $row['is_verified'];
            $code = $row['code'];

            if ($account_verified == "1") {
                $_SESSION['SESSION_LOGGED_IN'] = true;
                header("Location: home");
            } else {
                $mail = new PHPMailer(true);

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
                $mail->Body = '<h1 style="color:#4070f4;">Secure Auth</h1><p>Click the link provided below to verify your account and get access to our features.</p><b><a href="http://localhost/pd-finalProject/login.php?verification=' . $code . '">http://localhost/pd-finalProject/login.php?verification=' . $code . '</a></b>';

                $mail->send();

                $msg = "<div class='alert alert-danger'>Account Verification Needed!<p>Check your email to verify your account</p></div>";
            }
        } else {
            $msg = "<div class='alert alert-danger'>Invalid email or password</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>Captcha Verification Needed!<p>Please verify you are not a robot</p></div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>title-placeholder</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="css/styles.css" type="text/css" media="all" />
</head>

<body>
    <div class="container">
        <div class="forms">
            <div class="form login">
                <span class="title">Login to your account</span>
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
                                type="submit">LOGIN</button>
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