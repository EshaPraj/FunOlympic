<?php
session_start();
if (isset($_SESSION['SESSION_ADMIN'])) {
    if (!$_SESSION['SESSION_ADMIN']) {
        header("Location: /pd-finalProject/home");
        die();
    }
}

$id = $title = $description = $category = $createdAt = $categoryId = "";
$titleError = $descriptionError = $categoryError = $createdAtError = "";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    require "../config.php";

    //prepare an sql statement
    $sql = "SELECT n.id, n.title, n.description, n.created_at, c.id as category_id, c.name as category FROM news n inner join category c on c.id = n.category_id WHERE n.id = ?";

    if ($statement = $conn->prepare($sql)) {
        $statement->bind_param("i", $c_id);
    }

    $id = $_GET['id'];
    $c_id = trim($id);

    if ($statement->execute()) {
        $result = $statement->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            $id = $row["id"];
            $title = $row["title"];
            $description = $row["description"];
            $category = $row["category"];
            $createdAt = $row["created_at"];
            $categoryId = $row["category_id"];
        }
        $statement->close();
    }
    $conn->close();

}

// post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = trim($_POST['id']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = (int) trim($_POST['category']);
    $createdAt = trim($_POST['createdAt']);

    if ($title == '') {
        $titleError = 'Title field is required!';
    }

    if ($description == '') {
        $descriptionError = 'Description field is required!';
    }

    if ($category == '') {
        $categoryError = 'Category field is required!';
    }

    if ($createdAt == '') {
        $createdAtError = 'Created at field is required!';
    }

    if ($titleError == "" && $descriptionError == "" && $categoryError == "" && $createdAtError == "") {
        require "../config.php";

        $sql = "UPDATE news SET title='$title', description='$description', category_id=$category, created_at='$createdAt' WHERE id = $id";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            header("location: news-d.php");
            mysqli_close($conn);
        } else {
            echo "Error Updating record" . mysqli_error($conn);
        }
    }
}
?>

<?php
require "../config.php";

$query = "SELECT * FROM category";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $options = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
                <h1>EDIT NEWS</h1>
                <a href="news-d.php">
                    <i class="fas fa-arrow-left"></i>
                    BACK
                </a>
            </div>
            <div class="form-container">
                <form action="edit.php" method="POST">
                    <div class="form-group">
                        <label for="id">Id</label>
                        <div class="in-box">
                            <input style="border: none;" type="text" name="id" class="form-control" value="<?= $id; ?>">
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="title">Title</label>
                        <div class="in-box">
                            <input type="text" name="title" class="form-control" value="<?= $title; ?>">
                            <p class="text-danger-edit">
                                <?= $titleError ?>
                            </p>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <div class="in-box">
                            <textarea name="description" class="form-control" rows="6"><?= $description; ?></textarea>
                            <p class="text-danger-edit">
                                <?= $descriptionError ?>
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <div class="in-box">
                            <select name="category" class="form-control">
                                <option value=""></option>
                                <?php
                                foreach ($options as $option) {
                                    ?>
                                    <?php echo "<option value={$option['id']}" ?>
                                    <?php if ($categoryId == $option['id'])
                                        echo "selected" ?>
                                    <?php echo ">" ?>
                                    <?php echo "{$option['name']}" ?>
                                    </option>
                                <?php
                                }
                                ?>
                            </select>
                            <p class="text-danger-edit">
                                <?= $categoryError ?>
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for='createdAt'>Created At</label>
                        <div class="in-box">
                            <input type="datetime-local" value="<?= $createdAt; ?>" name="createdAt"
                                class="form-control">
                            <p class="text-danger-edit">
                                <?= $createdAtError ?>
                            </p>
                        </div>
                    </div>

                    <input type="Submit" value="EDIT BROADCAST" class="btn-submit">
                </form>
            </div>
        </div>
    </div>
    <?php require "../component/footer.php" ?>
</body>

</html>