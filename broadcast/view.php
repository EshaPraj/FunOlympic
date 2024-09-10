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
    $sql = 'SELECT b.id, b.title, b.description, c.name as category, b.location, b.gender_representation, b.url, b.starts_at, b.ends_at FROM broadcast b INNER JOIN category c on b.category_id = c.id WHERE b.id = ?;';

    if ($statement = $conn->prepare($sql)) {
        $statement->bind_param("i", $c_id);
    }

    $c_id = trim($_GET['id']);

    if ($statement->execute()) {
        $result = $statement->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            $id = $row["id"];
            $title = $row["title"];
            $description = $row["description"];
            $category = $row["category"];
            $location = $row["location"];
            $genderRepresentation = $row["gender_representation"];
            $url = $row["url"];
            $startsAt = $row["starts_at"];
            $endsAt = $row["ends_at"];
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
                <h1>VIEW BROADCAST</h1>
                <a href="broadcast-d.php">
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
                        <th>Title</th>
                        <td>
                            <?= $title ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>
                            <?= $description ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td>
                            <?= $category ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td>
                            <?= $location ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Gender Representation</th>
                        <td>
                            <?= $genderRepresentation ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Url</th>
                        <td>
                            <?= $url ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Starts At</th>
                        <td>
                            <?= $startsAt ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Ends At</th>
                        <td>
                            <?= $endsAt ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php require "../component/footer.php" ?>
</body>

</html>