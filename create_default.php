<?php

$dir = 'uploads/photos/';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

$width = 100;
$height = 100;
$image = imagecreatetruecolor($width, $height);

$bg = imagecolorallocate($image, 200, 200, 200);
imagefill($image, 0, 0, $bg);

$textColor = imagecolorallocate($image, 100, 100, 100);

$text = "User";
$font = 5; 
$textWidth = imagefontwidth($font) * strlen($text);
$textHeight = imagefontheight($font);
$x = ($width - $textWidth) / 2;
$y = ($height - $textHeight) / 2;
imagestring($image, $font, $x, $y, $text, $textColor);

imagepng($image, $dir . 'default.png');
imagedestroy($image);

$imageJpg = imagecreatetruecolor($width, $height);
$bgJpg = imagecolorallocate($imageJpg, 200, 200, 200);
imagefill($imageJpg, 0, 0, $bgJpg);
$textColorJpg = imagecolorallocate($imageJpg, 100, 100, 100);
imagestring($imageJpg, $font, $x, $y, $text, $textColorJpg);
imagejpeg($imageJpg, $dir . 'default.jpg', 90);
imagedestroy($imageJpg);

echo "✅ Default images created in " . $dir . "<br>";
echo "File: default.jpg and default.png<br>";
echo "Path: " . realpath($dir) . "<br>";
?>