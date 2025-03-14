<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

require 'Bot.php';

$bot = new Bot();

$update = json_decode(file_get_contents('php://input'));

$message = $update->message;
$cid = $message->chat->id;
$txt = $update->message->text;
$messageId = $update->message->message_id; // User yuborgan xabarni ID si
$name = $message->chat->first_name;
$user_name = $message->chat->username;


// INLINE KAYBOARD LARNI USHLAB OLISH
if (isset($update->callback_query)) {
    $callbackQuery = $update->callback_query; // Update ichida Callback query ni ushlab olib Callback queryni ishlatamiz
    $callbackText = $callbackQuery->data; // Tugma bosilgandagi so'z. Tugmani so'zi
    $callbackChatId = $callbackQuery->message->chat->id; // Foiydalanuvchini ID si callbackdagi
    $callMid = $callbackQuery->message->message_id; // Xabarni ID sini ushlab olish USERNIKI

}


//BUTTONS MENYU_MARKAP
$languages = json_encode([
    'resize_keyboard' => true,
    'keyboard' => [
        [['text' => 'UZB 🇺🇿'], ['text' => 'РУС 🇷🇺']],
    ]
]);


// FOR USERS
if ($txt == "/start") {
    $bot->makeRequest('sendMessage', [
        'chat_id' => $cid,
        'text' => "👋 <b>Assalomu alaykum hurmatli mijoz botimizga xush kelibsiz.</b>",
        'parse_mode' => "html",
        'reply_markup' => $languages,
    ]);
}

if ($txt == 'UZB 🇺🇿') {
    $bot->saveUser($cid, 30);  // Discount tabledan olib kelinadi
    $bot->makeRequest('sendVideo', [
        'chat_id' => $cid,
        'video' => "https://t.me/nurdavlatBlog/107",
        'caption' => "10 daqiqalik tekin darslik vd keldi uzb tilida"
    ]);

    $bot->makeRequest('sendMessage', [
        'chat_id' => $cid,
        'text' => "Videodan keyin yopiq kanal haqida reklama va takliflar yozilgan xabar jo‘natiladi. Xabar ichida obuna narxi bir oyga ekanligi haqida ma’lumot yoziladi.",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "Qo'shilish", 'callback_data' => "Qo'shilish"]],
            ]
        ]),
    ]);
}
if ($txt == 'РУС 🇷🇺') {
//    $discount = true; // Buni admindan ovollamiz
    $bot->saveUser($cid,23);  // Discount tabledan olib kelinadi
    $bot->makeRequest('sendVideo', [
        'chat_id' => $cid,
        'video' => "https://t.me/nurdavlatBlog/107",
        'caption' => "Прибыл бесплатный 10-минутный 
мастер-класс по русскому языку"
    ]);

    $bot->makeRequest('sendMessage', [
        'chat_id' => $cid,
        'text' => "После видео будет отправлено сообщение с рекламой и предложениями о закрытом канале. 
В сообщении написана информация, что стоимость подписки указана на один месяц.",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "Присоединение", 'callback_data' => "присоединение"]],
            ]
        ]),
    ]);
}

if ($callbackText == "Qo'shilish") {
    if ($bot->getUserInfo($callbackChatId)['balance'] >= 100000){
        $bot->makeRequest('sendMessage', [
            'chat_id' => $callbackChatId,
            'text' => "Sizning balanisingiz yetib ortvotti",
        ]);
    }else{
        $bot->makeRequest('sendMessage', [
            'chat_id' => $callbackChatId,
            'text' => "Siz yopiq kanalga qo'shilishingiz uchun hisobingida 100.000 so'm bo'lishi lozim",
            'reply_markup' => json_encode([
                'resize_keyboard' => true,
                'keyboard' => [
                    [['text' => "Click"], ['text' => "Payme"], ['text' => "Uzum"]],
                    [['text'=>"Balansni tekshirish"]],
                ]
            ]),
        ]);
    }

}

if ($callbackText == "присоединение") {
    if ($bot->getUserInfo($callbackChatId)['balance'] >= 100000){
        $bot->makeRequest('sendMessage', [
            'chat_id' => $callbackChatId,
            'text' => "Ваших денег достаточно для входа в закрытый канал",
        ]);
    }
    $bot->makeRequest('sendMessage', [
        'chat_id' => $callbackChatId,
        'text' => "Чтобы присоединиться к каналу, на вашем счету должно быть 100 сумов.",
        'reply_markup' => json_encode([
            'resize_keyboard' => true,
            'keyboard' => [
                [['text' => "Click"], ['text' => "Payme"], ['text' => "Uzum"]],
                [['text'=>"Проверка баланса"]],
            ]
        ]),
    ]);
}


if ($txt == "Balansni tekshirish"){
     $userSumma = $bot->getUserInfo($cid)['balance'];
    $bot->makeRequest('sendMessage', [
        'chat_id' => $cid,
        'text' => "Sizning hisobingizda " . $userSumma . " so'm pul bor"
    ]);
}






// ADMIN PAGE
if ($bot->getRoles((int )$cid)) {
    require_once 'Admin/admin.php';
}