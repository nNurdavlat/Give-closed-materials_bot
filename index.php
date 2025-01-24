<?php
// ADASHIB QOLMASLIK UCHUN. BU BOT: GIVE CLOSED MATERIALS
require 'Bot.php';

$bot = new Bot();

$update = json_decode(file_get_contents('php://input')); // Hamma malumot va o'zgarishlar keldi keldi // Xabarlarni qayta ishlash

$message = $update->message;
$cid = $message->chat->id;
$name = $message->chat->first_name;
$user_name = $message->chat->username;
$txt = $update->message->text; // Kelgan so'zni $text ga ozlashtirib qo'yamiz
//$contact = $update->message->contact;
$messageId = $update->message->message_id; // Xabarni ID sini ushlab olish Usernikini.


// INLINE KAYBOARD LARNI USHLAB OLISH
//if ($update->callback_query) {
//    $callbackQuery = $update->callback_query; // Update ichida Callback query ni ushlab olib Callback queryni ishlatamiz
//    $callbackText = $callbackQuery->data; // Tugma bosilgandagi so'z. Tugmani so'zi
//    $callbackChatId = $callbackQuery->message->chat->id; // Foiydalanuvchini ID si callbackdagi
//    $callMid = $callbackQuery->message->message_id; // Xabarni ID sini ushlab olish USERNIKI
//
//}

//BUTTONS MENYU_MARKAP
$languages = json_encode([
    'resize_keyboard' => true,
    'keyboard' => [
        [['text'=>'UZB 🇺🇿'],['text'=>'РУС 🇷🇺']],
    ]
]);


if ($txt == '/start') {
    $bot->makeRequest('sendMessage', [
        'chat_id' => $cid,
        'text' => "Select a language: ",
        'reply_markup' => $languages,
    ]);
}

if($txt=='UZB 🇺🇿'){
    return true;
}elseif ($txt == 'РУС 🇷🇺'){
    $bot->makeRequest('sendMessage', [
        'chat_id' => $cid,
        'text' => 'Select a language: ',
    ]);
}