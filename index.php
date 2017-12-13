<?php

$data = json_decode(file_get_contents('php://input'), true);
$token = '487727741:AAFKBAN4O3dANJZ0k7-5pALddJO_19EZoRE';
$user_id = $data['message']['chat']['id'];
$user_message = trim($data['message']['text']);
$gif = '';
$pic = '';
if (file_exists('gif-phrase.txt')) {
     $gif = json_decode(file_get_contents('gif-phrase.txt'), true);
}
if (file_exists('photo-phrase.txt')) {
     $pic = json_decode(file_get_contents('photo-phrase.txt'), true);
}

if (empty($gif)) {
     $gif = [
         'гиф',
         'Гиф',
         'Гифка',
         'гифка',
         'Гифку',
         'гифку',
         'gif',
     ];
}
if (empty($pic)) {
     $pic = [
         'Фото',
         'фото',
         'Фотка',
         'Фотография',
         'picture',
     ];
}
$gifreg = implode('||', $gif);
$picreg = implode('||', $pic);

preg_match('/' . $gifreg . '/U', $user_message, $matchgif);
preg_match('/' . $picreg . '/U', $user_message, $matchpic);
preg_match('/[0-9]/', $user_message, $number);
$countPost = 1;
if (!empty($number)) {
     $countPost = intval($number[0]);
}
if (in_array($user_message, $pic) || !empty($matchpic[0])) {
    $photo = file_get_contents('http://vk-send.tk/get-photo.php');
     $url = 'https://api.telegram.org/bot'.$token.'/sendPhoto?chat_id='.$user_id.'&photo='.$photo;
} elseif (in_array($user_message, $gif) || !empty($matchgif[0])) {
     //. '&message=' . urlencode('гифка')
     $gif = $db->getPostByUserId('gif', $countPost);
     foreach ($gif as $ph) {
          $sqr[] = 'doc' . $ph['owner_id'] . '_' . $ph['vk_item_id'] . '_' . $ph['access_key'];
     }
     $sqr = implode(',', $sqr);
     $arapi = 'random_id=' . $data->object->id . '&user_id=' . $user_id . '&attachment=' . $sqr;//. '&message=' . urlencode('фото')

     $vk->curlGet('messages.send?' . $arapi);
     file_put_contents('gif.txt', $user_message . '-' . $user_id . '-' . date('d.m.Y H:i:s') . "\n", 8);
} else {
     file_put_contents('words.txt', $user_message . '-' . $user_id . '-' . date('d.m.Y H:i:s') . "\n", 8);
}
file_get_contents($url);