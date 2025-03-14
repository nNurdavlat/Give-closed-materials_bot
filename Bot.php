<?php

require 'DB.php';
class Bot
{
    const  API_URL = 'https://api.telegram.org/bot';
    public function makeRequest($method, $data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL . $_ENV['BOT_TOKEN'] . '/' . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $responses = curl_exec($ch);
        curl_close($ch);
        return json_decode($responses);  // BOT YUBORGAN JAVOBNI QAYTARVOMIZ. USERNIKI NGROKDAN KO"RINADI
    }



    public function saveUser(int $chatId, int $discount)
    {
        $query = "INSERT INTO users (chat_id, balance, finished_follow) 
                        VALUES (:chatId, :balance, NOW())";
        $db = new DB();
        $stmt = $db->conn->prepare($query);
        $stmt->execute([
            ':chatId' => $chatId,
            ':balance' => $discount,
        ]);
    }


    public function getUserInfo(int $chatId)
    {
        $query = "SELECT * FROM users WHERE chat_id = :chatId";
        $db = new DB();
        $stmt = $db->conn->prepare($query);
        $stmt->execute([
            ':chatId' => $chatId,
        ]);
        return $stmt->fetch();
    }



    // FOR ADMINS
    public function getDiscount(int $chatId)
    {
        $query = "SELECT * FROM price WHERE chat_id = :chatId";
        $db = new DB();
        $stmt = $db->conn->prepare($query);
        $stmt->execute([
            ':chatId' => $chatId,
        ]);
        return $stmt->fetch();
    }

    public function updateDiscount(int $chatId, int $newDiscount): bool
    {
        $query = "UPDATE price SET discount = :newDiscount WHERE chat_id=:chatId";
        $db = new DB();
        $stmt = $db->conn->prepare($query);
        return $stmt->execute([
            ':chatId' => $chatId,
            ':newDiscount' => $newDiscount,
        ]);
    }

    public function checkAdmin($chatId)
    {
        $query = "SELECT * FROM admins WHERE chat_id = :chatId";
        $db = new DB();
        $stmt = $db->conn->prepare($query);
        return $stmt->execute([
            ":chatId" => $chatId,
        ]);
    }
}