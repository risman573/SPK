<?php

function telegram_kirim($token, $id, $pesan){
    $telegrambot = $token; 
    $website="https://api.telegram.org/".$telegrambot; 
    $chatId=$id; 
    $params=array('chat_id'=>$chatId,'text'=>$pesan, 'parse_mode' => 'HTML');    

    $ch = curl_init($website . '/sendMessage'); 
    curl_setopt($ch, CURLOPT_HEADER, false); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($params)); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    $result = curl_exec($ch); 
    curl_close($ch);
    return $result;
}