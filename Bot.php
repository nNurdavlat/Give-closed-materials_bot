<?php

require 'DB.php';
class Bot
{

    const  API_URL = 'https://api.telegram.org/bot';
    private string $bot_token = '7774855844:AAHJHqdgC6toUM5UxIx1DXeZ5O4yEBH3nPo';

    public function makeRequest($method, $data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL . $this->bot_token . '/' . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $responses = curl_exec($ch);
        curl_close($ch);
        return json_decode($responses);  // BOT YUBORGAN JAVOBNI QAYTARVOMIZ. USERNIKI NGROKDAN KO"RINADI
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
    public function getRoles(int $chatId)
    {
        $query = "SELECT role FROM users WHERE chat_id = :chatId";
        $db = new DB();
        $stmt = $db->conn->prepare($query);
        $stmt->execute([
            ':chatId' => $chatId,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC); // Assotsiativ massiv qaytaradi

        if ($row['role'] == 'admin') {
            return true; // Foydalanuvchining rolini qaytarish
        }
        return false; // Foydalanuvchi topilmadi
    }


    public function saveUser(int $chatId, string $lang, int $discount)
    {
        $query = "INSERT INTO users (chat_id, balance, language) 
                        VALUES (:chatId, :balance, :language);";
        $db = new DB();
        $stmt = $db->conn->prepare($query);
        $stmt->execute([
            ':chatId' => $chatId,
            ':language' => $lang,
            ':balance' => $discount,
        ]);
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
}