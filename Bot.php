<?php

require 'DB.php';
class Bot
{

    const  API_URL = 'https://api.telegram.org/bot';
    private string $bot_token = '7774855844:AAHJHqdgC6toUM5UxIx1DXeZ5O4yEBH3nPo';
    public function makeRequest($method, $data=[])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL . $this->bot_token . '/' . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $responses = curl_exec($ch);
        curl_close($ch);
        return json_decode($responses);  // BOT YUBORGAN JAVOBNI QAYTARVOMIZ. USERNIKI NGROKDAN KO"RINADI
    }


    public function saveUser(string $language): true
    {
        return true;
    }
}