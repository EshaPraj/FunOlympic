<?php
    session_start();

    header("Content-type: text/javascript");
    require "../config.php";

    if(isset($_GET['broadcast'])){
        $broadcastId = (int)mysqli_real_escape_string($conn, $_GET['broadcast']);
        $userId = (int)$_SESSION['SESSION_ID'];

        $sql = "SELECT * from broadcast_notification where broadcast_id=" . $broadcastId . " and user_id=" . $userId . ";";

        if ($result = $conn->query($sql)){
            if ($result->num_rows > 0){
                $query = "UPDATE `broadcast_notification` SET `notify`=0 WHERE `broadcast_id` = $broadcastId AND `user_id`=$userId;";
                mysqli_query($conn, $query);
            }else{
                $query = "INSERT INTO `broadcast_notification`(`broadcast_id`, `user_id`, `notify`) VALUES ({$broadcastId}, {$userId}, 0);";
                mysqli_query($conn, $query);
            }
        }
    }
?>