<?php
// Create a basic image file for the team members
$width = 300;
$height = 300;
$image = imagecreatetruecolor($width, $height);

// Set colors
$bg = imagecolorallocate($image, 155, 89, 182); // Purple background
$text_color = imagecolorallocate($image, 255, 255, 255); // White text

// Fill background
imagefill($image, 0, 0, $bg);

// Write text (User icon representation)
$text = "👤";
$font = 5; // Built-in font
$text_width = imagefontwidth($font) * strlen($text);
$text_height = imagefontheight($font);
$x = ($width - $text_width) / 2;
$y = ($height - $text_height) / 2;
imagestring($image, $font, $x, $y, $text, $text_color);

// Save image
$target_dir = 'uploads/team_members/';
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}
imagejpeg($image, $target_dir . 'default.jpg');
imagedestroy($image);

echo "Default image created successfully at: " . $target_dir . "default.jpg";
?> 