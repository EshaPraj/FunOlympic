<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/pd-finalProject/config.php";

$query = "SELECT * FROM category";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $options = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<aside style="padding-top: 24px; width: 240px">
    <section>
        <p><a style="<?php if (activeLinkContains('home') and !activeLinkContains('category'))
            echo "color: dodgerblue; font-weight:bold;" ?>"
                href="/pd-finalProject/home">Browse Broadcasts</a></p>
        <p><a style="<?php if (activeLinkContains('schedule.php'))
            echo "color: dodgerblue; font-weight:bold;" ?>"
                href="/pd-finalProject/schedule.php">View Schedule</a></p>
        <p><a style="<?php if (activeLinkContains('results.php'))
            echo "color: dodgerblue; font-weight:bold;" ?>"
                href="/pd-finalProject/results.php">View Results</a></p>
    </section>

    <?php
        if (isset($_SESSION['SESSION_ADMIN'])) {
            echo "<section>";
            echo '<h1 style="padding-top: 18px">Admin Section</h1>';
            echo '<p><a style="';
            if (activeLinkContains('user/'))
                echo "color: dodgerblue; font-weight:bold;";
            echo '" href="/pd-finalProject/user/user-d.php">User Management</a></p>';
            echo '<p><a style="';
            if (activeLinkContains('broadcast/'))
                echo "color: dodgerblue; font-weight:bold;";
            echo '" href="/pd-finalProject/broadcast/broadcast-d.php">Broadcast Management</a></p>';
            echo '<p><a style="';
            if (activeLinkContains('category/'))
                echo "color: dodgerblue; font-weight:bold;";
            echo '" href="/pd-finalProject/category/category-d.php">Category Management</a></p>';
            echo '<p><a style="';
            if (activeLinkContains('news/'))
                echo "color: dodgerblue; font-weight:bold;";
            echo '" href="/pd-finalProject/news/news-d.php">News Management</a></p>';
            echo '</section>';
        }
        ?>

    <section>
        <h1 style="padding-top: 18px">Categories</h1>
        <?php
        foreach ($options as $option) {
            echo "<p><a style='";
            if (activeLinkContains("category=" . (int) $option['id']))
                echo "color: dodgerblue; font-weight:bold;";
            echo "' href='/pd-finalProject/home?category={$option['id']}'>{$option['name']}</a></p>";
        }
        ?>
    </section>
</aside>