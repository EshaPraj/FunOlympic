<?php
    session_start();

    include 'config.php';

    $msg = "";

    if (isset($_GET['reset'])) {
        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE code='{$_GET['reset']}'")) > 0) {
            if (isset($_POST['submit'])) {
                $password = mysqli_real_escape_string($conn, $_POST['password']);
                $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm-password']);
                $password = mysqli_real_escape_string($conn, $_POST['password']);
                $salt = "D;%yL9TS:5PalS/d";
                $hashedPassword = hash('sha256', $password . $salt);

                if ($password === $confirm_password) {
                    $uppercase = preg_match('@[A-Z]@', $password);
                    $lowercase = preg_match('@[a-z]@', $password);
                    $number = preg_match('@[0-9]@', $password);
                    $specialChars = preg_match('@[^\w]@', $password);

                    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 12) {
                        $msg = "<div class='alert alert-danger'>Password should be at least 12 characters in length, should include at least one upper case letter, one number, and one special character</div>";
                    } else {
                        $sqlSelect = "SELECT * FROM users WHERE email='{$_SESSION['SESSION_EMAIL']}'";
                        $result = mysqli_query($conn, $sqlSelect);

                        if (mysqli_num_rows($result) === 1) {
                            $row = mysqli_fetch_assoc($result);
                            $passwd = $row["password"];

                            if ($passwd == $hashedPassword) {
                                $msg = "<div class='alert alert-danger'>Can not use previously used password</div>";
                            } else {
                                $query = "UPDATE users SET password='$hashedPassword' WHERE email='{$_SESSION['SESSION_EMAIL']}'";
                                $res =mysqli_query($conn, $query);

                                if($res){
                                    $msg = "<div class='alert alert-success'>Password changed sucessfully.</br>Please login to continue</div>";
                                }else{
                                    $msg = "<div class='alert alert-danger'>Some Error Occured</div>";
                                }
                            }
                        }
                    }
                } else {
                    $msg = "<div class='alert alert-danger'>Password and Confirm Password doesn't match</div>";
                }
            }
        } else {
            $msg = "<div class='alert alert-danger'>Invalid Link</div>";
        }
    } else {
        if (isset($_SESSION['SESSION_LOGGED_IN'])) {
            header("Location: home");
            die();
        }
    }
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>title-placeholder</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="UTF-8" />

        <link rel="stylesheet" href="css/styles.css" type="text/css" media="all" />
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    </head>
    <body>
        <div class="container">
            <div class="forms">
                <div class="form login">
                    <span class="title">Password Change</span>
                    <?php echo $msg; ?>

                    <form action="" method="post">
                        <div class="input-field">
                            <input id="psw-input" type="password" class="form-control password" name="password"
                                placeholder="Enter new Password" style="margin-bottom: 2px;" required>
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

                        <div class="input-field button">
                            <button name="submit" style="width: 100%; height: 50px" type="submit">Change</button>
                        </div>
                    </form>
                    <div class="login-signup">
                        <span class="text">
                            <a href="login.php" class="text login-link">Go to Login</a>
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
    </body>
</html>