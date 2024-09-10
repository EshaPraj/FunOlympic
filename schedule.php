<?php
session_start();

$sql;
require "./config.php";

$viewingSchedules = true;
$userId = (int) $_SESSION['SESSION_ID'];

if (isset($_GET['broadcast'])) {
    $viewingSchedules = false;
    $broadcastId = (int) mysqli_real_escape_string($conn, $_GET['broadcast']);
    $sql = "SELECT b.id, b.title, b.description, c.name as category, b.url, b.location, b.gender_representation, b.starts_at, b.ends_at FROM broadcast b INNER JOIN category c on b.category_id = c.id WHERE b.id={$broadcastId};";
} else {
    $sql = 'SELECT b.id, b.title, b.description, b.category_id, c.name as category, b.location, b.gender_representation, b.starts_at, b.ends_at FROM broadcast b INNER JOIN category c on b.category_id = c.id;';
    $viewingSchedules = true;
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
    <link rel="stylesheet" type="text/css" href="/pd-finalProject/css/style.css">
</head>

<body>
    <?php require "./component/header.php" ?>
    <div class="contain-main">
        <?php require "./component/sidebar.php" ?>
        <div class="box">
            <div>
                <?php
                if ($viewingSchedules) {
                    echo '<div class="contain-title" style="padding-bottom: 24px;">';
                    echo '<h1>Upcomming Broadcasts</h1>';
                    echo '</div>';
                    echo '<div>';
                    if ($result = $conn->query($sql)) {
                        if ($result->num_rows > 0) {
                            $broadcasts = mysqli_fetch_all($result, MYSQLI_ASSOC);

                            $startDates = array();

                            foreach ($broadcasts as $broadcast) {
                                $startDateTime = new DateTime($broadcast['starts_at']);
                                $formattedStartDate = $startDateTime->format('Y-m-d');

                                $startDates[$formattedStartDate] = $startDateTime;
                            }

                            // Sort the start dates in ascending order
                            usort($startDates, function ($a, $b) {
                                return $a <=> $b;
                            });

                            $startDates = array_values($startDates);

                            $currentDateTime = new DateTime();
                            $tomorrowDate = new DateTime('tomorrow');

                            $startDates = array_filter($startDates, function ($startDate) use ($currentDateTime) {
                                return $startDate >= $currentDateTime;
                            });

                            foreach ($startDates as $startDate) {

                                $broadcastList = array();
                                foreach ($broadcasts as $broadcast) {
                                    $startDateTime = new DateTime($broadcast['starts_at']);
                                    if ($startDate->format('Y-m-d') === $startDateTime->format('Y-m-d')) {
                                        array_push($broadcastList, $broadcast);
                                    }
                                }

                                array_filter($broadcastList, function ($e) {
                                    return $currentDateTime < $startDate;
                                });

                                $formattedStartDate = $startDate->format('Y-m-d');

                                if (($startDate->format('Y-m-d') === $currentDateTime->format('Y-m-d'))) {
                                    echo '<div style="padding-bottom: 22px;">';
                                    echo "<h3 style='padding-bottom: 12px;'>Today</h3>";
                                    echo "<div style='display: flex; flex-direction: column; gap: 8px'>";
                                    foreach ($broadcastList as $broadcast) {
                                        echo "<div style='border: 1px solid rgba(0, 0, 0, 0.2); padding: 12px; border-radius: 6px; max-width: fit-content;'>";
                                        echo '<p style="font-weight: 600; padding-bottom: 4px;">' . $broadcast['title'] . '</p>';
                                        echo '<p style="max-height: 44px; overflow: hidden;display: -webkit-box; -webkit-box-orient: vertical;-webkit-line-clamp: 2;"> ' . $broadcast['description'] . '</p>';
                                        echo '<p style="padding-top: 4px; padding-bottom: 12px;"> <span style="font-weight: 500;">Time:</span> ' . $broadcast['starts_at'] . ' - ' . $broadcast['ends_at'] . '</p>';
                                        echo '<a href="/pd-finalProject/schedule.php?broadcast=' . $broadcast['id'] . '" role="button" style="background-color: cornflowerblue;color: white;padding: 8px 12px; border-radius: 6px;display: inline-block;">View More</a>';
                                        echo "</div>";
                                    }
                                    echo "</div>";
                                    echo '</div>';
                                } else if (($startDate->format('Y-m-d') === $tomorrowDate->format('Y-m-d'))) {
                                    echo '<div style="padding-bottom: 22px;">';
                                    echo "<h3 style='padding-bottom: 12px;'>Tomorrow</h3>";
                                    echo "<div style='display: flex; flex-direction: column; gap: 8px'>";
                                    foreach ($broadcastList as $broadcast) {
                                        echo "<div style='border: 1px solid rgba(0, 0, 0, 0.2); padding: 12px; border-radius: 6px;'>";
                                        echo '<p style="font-weight: 600; padding-bottom: 4px;">' . $broadcast['title'] . '</p>';
                                        echo '<p style="max-height: 44px; overflow: hidden;display: -webkit-box; -webkit-box-orient: vertical;-webkit-line-clamp: 2;"> ' . $broadcast['description'] . '</p>';
                                        echo '<p style="padding-top: 4px; padding-bottom: 12px;"> <span style="font-weight: 500;">Time:</span> ' . $broadcast['starts_at'] . ' - ' . $broadcast['ends_at'] . '</p>';
                                        echo '<a href="/pd-finalProject/schedule.php?broadcast=' . $broadcast['id'] . '" role="button" style="background-color: cornflowerblue;color: white;padding: 8px 12px; border-radius: 6px; display: inline-block;">View More</a>';
                                        echo "</div>";
                                    }
                                    echo "</div>";
                                    echo '</div>';
                                } else {
                                    echo '<div style="padding-bottom: 22px;">';
                                    echo "<h4 style='padding-bottom: 12px;'>$formattedStartDate</h4>";
                                    echo "<div style='display: flex; flex-direction: column; gap: 8px'>";
                                    foreach ($broadcastList as $broadcast) {
                                        echo "<div style='border: 1px solid rgba(0, 0, 0, 0.2); padding: 12px; border-radius: 6px;'>";
                                        echo '<p style="font-weight: 600; padding-bottom: 4px;">' . $broadcast['title'] . '</p>';
                                        echo '<p style="max-height: 44px; overflow: hidden;display: -webkit-box; -webkit-box-orient: vertical;-webkit-line-clamp: 2;"> ' . $broadcast['description'] . '</p>';
                                        echo '<p style="padding-top: 4px; padding-bottom: 12px;"> <span style="font-weight: 500;">Time:</span> ' . $broadcast['starts_at'] . ' - ' . $broadcast['ends_at'] . '</p>';
                                        echo '<a href="/pd-finalProject/schedule.php?broadcast=' . $broadcast['id'] . '" role="button" style="background-color: cornflowerblue;color: white;padding: 8px 12px;border-radius: 6px; display: inline-block;">View More</a>';
                                        echo "</div>";
                                    }
                                    echo "</div>";
                                    echo '</div>';
                                }
                            }
                        } else {
                            echo "<p style='font-size: 18px;'>No Broadcasts Found</p>";
                        }
                    }
                    echo "</div>";
                } else {
                    // Single Broadcast
                    if ($result = $conn->query($sql)) {
                        if ($result->num_rows == 1) {
                            $row = $result->fetch_assoc();

                            $broadcastId = (int) $row['id'];
                            $query = "SELECT * from broadcast_notification where broadcast_id=" . $broadcastId . " and user_id=" . $userId . ";";
                            $checked;

                            if ($r = $conn->query($query)) {
                                if ($r->num_rows > 0) {
                                    $ro = $r->fetch_assoc();
                                    $checked = $ro['notify'] === '1' ? TRUE : FALSE;
                                } else {
                                    $checked = FALSE;
                                }
                            }
                            echo "<div>";
                            echo "<h2>" . $row['title'] . "</h2>";
                            echo "<h3 style='padding-top: 12px; padding-bottom: 6px'>Description</h3>";
                            echo "<p>" . $row['description'] . "</p>";
                            echo "<h3 style='padding-top: 12px; padding-bottom: 6px'>Category</h3>";
                            echo "<p>" . $row['category'] . "</p>";
                            echo "<h3 style='padding-top: 12px; padding-bottom: 6px'>Location</h3>";
                            echo "<p>" . $row['location'] . "</p>";
                            echo "<h3 style='padding-top: 12px; padding-bottom: 6px'>Gender Representation</h3>";
                            echo "<p>" . $row['gender_representation'] . "</p>";
                            echo "<h3 style='padding-top: 12px; padding-bottom: 6px'>Time</h3>";
                            echo "<p>" . $row['starts_at'] . ' - ' . $row['ends_at'] . "</p>";
                            echo '<label role="button" style="display: inline-flex; align-items: center; gap: 6px; margin-top: 18px; cursor: pointer;">';
                            echo '<input type="checkbox" ';
                            echo $checked ? 'checked' : "";
                            echo ' data-broadcast-id="' . $row['id'] . '" onclick="check(this);" value="' . $row['id'] . '" />';
                            echo 'Get Notified?';
                            echo '</label>';
                            echo "</div>";
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <?php require "./component/footer.php" ?>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script>
        function check(cb) {
            const broadcastId = cb.getAttribute("data-broadcast-id");
            if ($(cb).is(":checked")) {
                $.getScript('./actions/checkCheckbox.php?broadcast=' + broadcastId);
            } else {
                $.getScript('./actions/uncheckCheckbox.php?broadcast=' + broadcastId);
            }
        }
    </script>
</body>

</html>