<?php
session_start();
if (isset($_SESSION['SESSION_LOGGED_IN'])) {
    header("Location: dashboard.php");
    die();
}

include 'config.php';
$msg = "";

if (isset($_POST['submit'])) {
    $otpInput = mysqli_real_escape_string($conn, $_POST['otp']);
    $sessionEmail = mysqli_real_escape_string($conn, $_SESSION['SESSION_EMAIL']);
    $sql = "SELECT * FROM users WHERE email='{$_SESSION['SESSION_EMAIL']}'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $otp = $row["otp"];

        if ($otp == $otpInput) {
            $_SESSION['SESSION_LOGGED_IN'] = true;
            mysqli_query($conn, "UPDATE users SET otp=NULL WHERE email='{$sessionEmail}'");
            header("Location: dashboard.php");
        } else {
            $msg = "<div class='alert alert-danger'>Invalid OTP</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>SecureAuth | OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="css/styles.css" type="text/css" media="all" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>

<body>
    <div class="container">
        <div class="forms">
            <div class="form login">
                <span class="title">Enter OTP</span>
                <?php echo $msg; ?>

                <form action="" method="post">
                    <div class="input-field">
                        <input type="number" class="number" name="otp" placeholder="Enter your OTP" required>
                        <i class="uil uil-dialpad-alt icon"></i>
                    </div>
                    <div class="input-field button">
                        <button name="submit" name="submit" style="width: 100%; height: 50px"
                            type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>