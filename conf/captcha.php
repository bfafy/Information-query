<?php
if (!function_exists('imagecreatetruecolor')) {
    echo 'GD 扩展不可用，无法生成验证码';
    exit;
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 设置验证码图片的宽度和高度
$width = 100;
$height = 30;

// 创建验证码图片
$image = imagecreatetruecolor($width, $height);

// 设置背景颜色
$bgColor = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $bgColor);

// 生成随机验证码
$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$code = '';
for ($i = 0; $i < 4; $i++) {
    $code .= $chars[rand(0, strlen($chars) - 1)];
}

// 将验证码存储到session中
$_SESSION['code'] = $code;

// 将验证码绘制到图片上
$textColor = imagecolorallocate($image, 0, 0, 0);
imagestring($image, 5, 30, 10, $code, $textColor);

// 添加干扰线
for ($i = 0; $i < 3; $i++) {
    $lineColor = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
    imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $lineColor);
}

// 输出图片
header('Content-Type: image/png');
imagepng($image);

// 销毁图片资源
imagedestroy($image);
?>
