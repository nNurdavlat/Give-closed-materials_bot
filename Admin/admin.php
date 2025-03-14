<?php

$discountsButtons= json_encode([
    'resize_keyboard' => true,
    'keyboard' => [
        [['text'=>"Chegirma narxi"],['text'=>"Chegirma summasini yangilash"]],
    ]
]);


if ($txt == '/admin'){
    $bot->makeRequest('sendMessage', [
        'chat_id' => $cid,
        'text'=>"Assalomu aleykum. Admin panelga xush kelibsiz.",
        'reply_markup' => $discountsButtons,
    ]);
}


if ($txt == "Chegirma narxi"){
    $discount = $bot->getDiscount($cid);
    $bot->makeRequest('sendMessage', [
        'chat_id' => $cid,
        'text' => "Hozirgi chegirma narxi: " . $discount['discount'] ,
    ]);
}

if ($txt == "Chegirma summasini yangilash"){
    $bot->makeRequest('sendMessage', [
        'chat_id' => $cid,
        'text' => "Chegirma summasini kiriting (100000): ",
    ]);

    file_put_contents("step/$cid.txt", "ask_cost");
}


elseif (file_get_contents("step/$cid.txt") == "ask_cost"){
    file_put_contents("step/{$cid}_cost.txt", $txt); // Summa ozildi

    $summa = file_get_contents("step/{$cid}_cost.txt"); // O'zgaruvchiga summani ovoldik

    if ($bot->updateDiscount($cid, $summa)){
        $bot->makeRequest('sendMessage', [
            'chat_id' => $cid,
            'text' => "Chegirma narxi saqlandi. Ishonch hosil qilish uchun tekshirib ko'ring",
        ]);
    }else{
        $bot->makeRequest('sendMessage', [
            'chat_id' => $cid,
            'text' => "Chummo ekansan",
        ]);
    }
    unlink("step/$cid.txt");  // step ichidagi filelari o'chirvorishimiz kerak
    unlink("step/{$cid}_cost.txt");
}

