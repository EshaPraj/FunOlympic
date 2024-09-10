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
    $sql = "SELECT n.id, n.title, n.description, n.created_at, c.name as category FROM news n inner join category c on n.category_id = c.id WHERE n.id = ?";

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
            $title = $row["title"];
            $description = $row["description"];
            $createdAt = $row["created_at"];
            $category = $row["category"];
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
                <h1>VIEW NEWS</h1>
                <a href="news-d.php">
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
                        <th>Created At</th>
                        <td>
                            <?= $createdAt ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <?php require "../component/footer.php" ?>
</body>

</html>