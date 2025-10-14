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
?>