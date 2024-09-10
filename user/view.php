<?php
session_start();
if (isset($_SESSION['SESSION_ADMIN'])) {
    if (!$_SESSION['SESSION_ADMIN']) {
        header("Location: /pd-finalProject/home");
        die();
    }
}

$name = $email = $id = $password = "";
if (isset($_GET['id']) && !empty($_GET['id'])) {
    require "../config.php";

    //prepare an sql statement
    $sql = "SELECT * FROM users WHERE id = ?";

    if ($statement = $conn->prepare($sql)) {
        $statement->bind_param("i", $c_id);
    }

    $c_id = trim($_GET['id']);

    if ($statement->execute()) {
        $result = $statement->get_result();

        if ($result->num_rows == 1) {
            //fetch result  row as a associative array
            $row = $result->fetch_assoc();

            $id = $row["id"];
            $name = $row["name"];
            $email = $row["email"];
            $password = $row["password"];
            $is_admin = $row["is_admin"] ? "Yes" : "No";
            $is_verified = $row["is_verified"] ? "Yes" : "No";
        }
        $statement->close();
    }
    $conn->close();

}
?>

<!DOCTYPE html>
<html>

<head>
    <title>title-placeholer</title>
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
                <h1>VIEW USER</h1>
                <a href="user-d.php">
                    <i class="fas fa-arrow-left"></i>
                    BACK
                </a>
            </div>
            <div class="box-user">
                <table>
                    <tr>
                        <th>Id</th>
                        <td>
                            <?= $id ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>
                            <?= $name ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>
                            <?= $email ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td>
                            <?= $password ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Verified</th>
                        <td>
                            <?= $is_verified ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Admin</th>
                        <td>
                            <?= $is_admin ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <?php require "../component/footer.php" ?>
</body>

</html>