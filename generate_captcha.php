<?php
session_start();

$width = 100;
$height = 40;
$captcha_image = imagecreatetruecolor($width, $height);

// Tạo màu
$background_color = imagecolorallocate($captcha_image, 255, 255, 255); 
$text_color = imagecolorallocate($captcha_image, 0, 0, 0); 

// Tô màu nền
imagefilledrectangle($captcha_image, 0, 0, $width, $height, $background_color);

// Tạo mã captcha
$captcha_code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 5);
$_SESSION['captcha_code'] = $captcha_code; // Lưu vào session

// Viết mã captcha lên hình
imagettftext($captcha_image, 20, 0, 10, 30, $text_color, 'arial.ttf', $captcha_code);

// Xuất hình ảnh
header('Content-Type: image/png'); 
imagepng($captcha_image);
imagedestroy($captcha_image);
?>
