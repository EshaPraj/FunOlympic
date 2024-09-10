<?php
session_start();
if (isset($_SESSION['SESSION_ADMIN'])) {
    if (!$_SESSION['SESSION_ADMIN']) {
        header("Location: /pd-finalProject/home");
        die();
    }
}

$email = $name = $password = $c_password = "";
$nameError = $emailError = $passwordError = $c_passwordError = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $c_passwd = trim($_POST['c_password']);
    $verified = trim($_POST['is_verified']) == "on" ? 1 : 0;
    $admin = trim($_POST['is_admin']) == "on" ? 1 : 0;

    if ($name == '') {
        $nameError = 'Name field is required!';
    }

    if ($email == '') {
        $emailError = 'Email field is required!';
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = 'Invalid email format!';
        }
    }

    if ($password == '') {
        $passwordError = 'Password field is required!';
    } else {
        $passlen = strlen($password);
        if ($passlen < 9) {
            $passwordError = '8 character long password requred!';
        }
    }

    if ($c_passwd == '') {
        $c_passwordError = 'Password confirmation is required!';
    }

    if ($password != $c_passwd) {
        $passwordError = 'Password doesnot match!';
    }

    if ($nameError == "" && $emailError == "" && $passwordError == "" && $c_passwordError == "") {
        require "../config.php";

        $salt = "D;%yL9TS:5PalS/d";
        $hashedPassword = hash('sha256', $password . $salt);

        $sql = "INSERT INTO users (name, email, password, is_verified, is_admin) VALUES ('{$name}', '{$email}', '{$hashedPassword}', '{$verified}', '{$admin}')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            header("location: user-d.php");
            $conn->close();
        } else {
            echo "Error Inserting record" . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>title-placeholder</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

<body>
    <?php require "../component/header.php" ?>
    <div class="contain-main">
        <?php require "../component/sidebar.php" ?>
        <div class="box">
            <div class="contain-title">
                <h1>User Create</h1>
                <p>
                    <a href="user-d.php">
                        <i class="fas fa-arrow-left"></i>
                        BACK
                    </a>
                </p>
            </div>
            <div class="form-container">
                <form action="create.php" method="POST">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <div class="in-box">
                            <input type="text" name="name" class="form-control" value="<?= $name; ?>">
                            <p class="text-danger-edit">
                                <?= $nameError ?>
                            </p>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="name">Email</label>
                        <div class="in-box">
                            <input type="email" name="email" class="form-control" value="<?= $email; ?>">
                            <p class="text-danger-edit">
                                <?= $emailError ?>
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for='password'>Password</label>
                        <div class="in-box">
                            <input type="password" name="password" class="form-control">
                            <p class="text-danger-edit">
                                <?= $passwordError ?>
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for='c_password'>Confirm Password</label>
                        <div class="in-box">
                            <input type="password" name="c_password" class="form-control">
                            <p class="text-danger-edit">
                                <?= $c_passwordError ?>
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for='is_admin'>Admin</label>
                        <div class="in-box">
                            <input type="checkbox" name="is_admin" <?= $is_admin == "1" ? "checked" : "" ?>
                                class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for='is_verified'>Verified</label>
                        <div class="in-box">
                            <input type="checkbox" name="is_verified" class="form-control" <?= $is_verified == "1" ? "checked" : "" ?>>
                        </div>
                    </div>

                    <input type="Submit" value="CREATE USER" class="btn-submit">
                </form>
            </div>
        </div>
    </div>
    <?php require "../component/footer.php" ?>
</body>

</html>