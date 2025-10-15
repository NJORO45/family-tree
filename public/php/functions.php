<?php
function sanitize($input) {
    if (!is_string($input)) return $input;

    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
function random_num($length){
	$text ="";
	if ($length<5) {
		$length=5;
	}
	$len=rand(4,$length);
	for ($i=0; $i < $len ; $i++) { 
		$text .= rand(0,9);
	}
	return $text;
}
function convertToWebP($input, $output, $quality = 80) {
    $ext = strtolower(pathinfo($input, PATHINFO_EXTENSION));
    $image = null;

    switch ($ext) {
        case 'jpg':
        case 'jpeg':
        case 'jfif':
            $image = imagecreatefromjpeg($input);
            break;
        case 'png':
            $image = imagecreatefrompng($input);
            break;
        case 'gif':
            $image = imagecreatefromgif($input);
            break;
        default:
            return false; // Unsupported format
    }

    if (!$image) {
        return false;
    }

    // Create WebP file
    $result = imagewebp($image, $output, $quality);
    imagedestroy($image);
    return $result;
}
?>