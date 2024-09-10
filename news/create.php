<?php
session_start();
if (isset($_SESSION['SESSION_ADMIN'])) {
    if (!$_SESSION['SESSION_ADMIN']) {
        header("Location: /pd-finalProject/home");
        die();
    }
}

$title = $description = $category = "";
$titleError = $descriptionError = $categoryError = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);

    if ($title == '') {
        $titleError = 'Title field is required!';
    }

    if ($description == '') {
        $descriptionError = 'Description field is required!';
    }

    if ($category == '') {
        $categoryError = 'Category field is required!';
    }

    if ($titleError == "" && $descriptionError == "" && $categoryError == "") {
        require "../config.php";

        $sql = "INSERT INTO news (title, description, category_id, created_at) VALUES ('{$title}', '{$description}', '{$category}', NOW())";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            header("location: news-d.php");
            $conn->close();
        } else {
            echo "Error Inserting record" . mysqli_error($conn);
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
                <h1>News Create</h1>
                <p>
                    <a href="news-d.php">
                        <i class="fas fa-arrow-left"></i>
                        BACK
                    </a>
                </p>
            </div>
            <div class="form-container">
                <form action="create.php" method="POST">
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
                            <input type="text" name="description" class="form-control" value="<?= $description; ?>">
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
                                    <?php if ($category == $option['id'])
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
                    <input type="Submit" value="CREATE NEWS" class="btn-submit">
                </form>
            </div>
        </div>
    </div>
    <?php require "../component/footer.php" ?>
</body>

</html>