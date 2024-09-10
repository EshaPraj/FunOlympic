<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if (isset($_SESSION['SESSION_LOGGED_IN'])) {
    header("Location: home");
    die();
}

require 'vendor/autoload.php';
include 'config.php';

$msg = "";

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, ($_POST['password']));
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm-password']);
    $code = mysqli_real_escape_string($conn, md5(rand()));
    $is_admin = 0;
    $is_verified = 0;

    $sk = $_POST['g-recaptcha-response'];
    $site_key = "6LdR5S4lAAAAAMMink7zrczxd9qituO_2els-qMs";
    $ip = $_SERVER['REMOTE_ADDR'];
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$site_key&response=$sk&remoteip=$ip";
    $fire = file_get_contents($url);
    $data = json_decode($fire, true);

    if ($data['success'] == "true") {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 12) {
            $msg = "<div class='alert alert-danger'>Password should be at least 12 characters in length, should include at least one upper case letter, one number, and one special character</div>";
        } else {
            $salt = "D;%yL9TS:5PalS/d";
            $hashedPassword = hash('sha256', $password . $salt);

            if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email='{$email}'")) > 0) {
                $msg = "<div class='alert alert-danger'>This email is already registered</div>";
            } else {
                if ($password === $confirm_password) {
                    $sql = "INSERT INTO users (name, email, password, code, is_admin, is_verified) VALUES ('{$name}', '{$email}', '{$hashedPassword}', '{$code}', '{$is_admin}', '{$is_verified}')";
                    $result = mysqli_query($conn, $sql);

                    if ($result) {
                        echo "<div style='display: none;'>";
                        $mail = new PHPMailer(true);

                        try {
                            $mail->isSMTP();
                            $mail->SMTPDebug = 1;
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
                            echo 'Message has been sent';
                        } catch (Exception $e) {
                            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        }
                        echo "</div>";
                        $msg = "<div class='alert alert-info'>Check your email to verify your account</div>";
                        $name = "";
                        $email = "";
                    } else {
                        $msg = "<div class='alert alert-danger'>Some Error Occured</div>";
                    }
                } else {
                    $msg = "<div class='alert alert-danger'>Password and Confirm password doesn't match</div>";
                }
            }
        }
    } else {
        $msg = "<div class='alert alert-danger'>Verification Needed<p>Please verify you are not a robot</p></div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>title-placeholder</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />

    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="css/styles.css" type="text/css" media="all" />
</head>

<body>
    <div class="container">
        <div class="forms">
            <div class="form login">
                <span class="title">Create an account</span>
                <?php echo $msg; ?>

                <form action="" method="post">
                    <div class="input-field">
                        <input type="text" class="name" name="name" placeholder="Enter your name" value="<?php if (isset($_POST['submit'])) {
                            echo $name;
                        } ?>" required>
                        <i class="uil uil-user"></i>
                    </div>
                    <div class="input-field">
                        <input type="email" class="email" name="email" placeholder="Enter your Email" value="<?php if (isset($_POST['submit'])) {
                            echo $email;
                        } ?>" required>
                        <i class="uil uil-envelope icon"></i>
                    </div>
                    <div class="input-field">
                        <input id="psw-input" type="password" class="form-control password" name="password"
                            placeholder="Enter your Password" style="margin-bottom: 2px;" required>
                        <i class="uil uil-lock icon"></i>
                    </div>
                    <div id="pswmeter"></div>
                    <div id="pswmeter-message" style="margin-top: 8px"></div>

                    <div class="input-field">
                        <input type="password" class="confirm-password password" name="confirm-password"
                            placeholder="Confirm your Password" required>
                        <i class="uil uil-lock icon"></i>
                        <i class="uil uil-eye-slash showHidePw"></i>
                    </div>

                    <div class="form-group" style="margin-top: 16px;">
                        <div style="display:flex;justify-content:center;" class="g-recaptcha"
                            data-sitekey="6LdR5S4lAAAAADzc7VvH4-LJ6L4uNpT4P8YQZ2MK">
                        </div>

                        <div class="input-field button">
                            <button name="submit" style="width: 100%; height: 50px" type="submit">CREATE
                                ACCOUNT</button>
                        </div>
                    </div>
                </form>
                <div class="login-signup">
                    <span class="text">Already a member?
                        <a href="login.php" class="text login-link">Login Now</a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <script src="js/password_validator.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        const myPassMeter = passwordStrengthMeter({
            containerElement: '#pswmeter',
            passwordInput: '#psw-input',
            showMessage: true,
            messageContainer: '#pswmeter-message',
            messagesList: [
                ' ',
                'Weak!',
                'Moderate',
                'Better',
                'Strong'
            ],
            height: 6,
            borderRadius: 0,
            pswMinLength: 8,
            colorScore1: 'red',
            colorScore2: 'yellow',
            colorScore3: 'blue',
            colorScore4: 'limegreen'
        });
    </script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>

</html>