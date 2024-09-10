<?php
    require "../config.php";

    $sql = "UPDATE users set name='Prabin Shrestha' where id=33";
    $result =mysqli_query($conn, $sql);

    echo "Command run sucessfully";
?>