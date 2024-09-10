<?php

class Chat extends Core
{

    public function escape($e){
       // $var = htmlentities($e);
        return htmlspecialchars($e,ENT_QUOTES);
    }

    public function fetchMessage($broadcastId)
    {
        $id = (int)$broadcastId;
        $this->query("
            SELECT  `chat`.`message`,
                    `chat`.`message_id`,
                     `chat_user`.`username`,
                     `chat_user`.`user_id`
            FROM      `chat`
            JOIN      `chat_user`
            ON          `chat`.`user_id` = `chat_user`.`user_id`
            WHERE `chat`.`broadcast_id` = $id
            ORDER BY    `chat`.`timestamp`
            DESC 
        ");

        return $this->rows();
    }

    public function throwMessage($user_id, $message, $broadcastId)
    {
        $id  = (int)$broadcastId;
        $this->query("
        INSERT INTO `chat` (`user_id`, `broadcast_id`, `message`,`timestamp`)
        VALUES ('$user_id', '$broadcastId', '$message',NOW())
        ");
    }

    public function signUp($username)
    {
        $this->query("
        insert into chat_user SET 
        username = '$username';
        ");
    }

    public function getId($username)
    {
        $this->query("
      SELECT * FROM `chat_user` WHERE username = '$username'
        ");

        return $this->rows();
    }


    public function delete($messageId)
    {
        $this->query("
        DELETE FROM `chat` WHERE `message_id` = $messageId
        ");

    }

    public function edit($msg, $messageId)
    {
        $this->query("
        UPDATE `chat` SET `message` = '$msg' WHERE `message_id` = $messageId;

        ");
    }

    public function getUser($user){
        $this->query("
        select * from chat_user where username = '$user'
        ");
        return $this->rows();
    }

}
