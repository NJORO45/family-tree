<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); // Use E_ALL during development
// Allow CORS (for JavaScript fetch requests)
header("Access-Control-Allow-Origin: *");
/*if using google phonts, cdns,  or external apis  u need to white list them here e.g
* script-src 'self' https://cdnjs.cloudflare.com; 
*style-src 'self' https://fonts.googleapis.com;
*font-src 'self' https://fonts.gstatic.com;
*/
//put all in one line
header("Content-Security-Policy: default-src 'self'; script-src 'self'; img-src 'self' data:; connect-src 'self'; form-action 'self'; base-uri 'self'; frame-ancestors 'self';");

date_default_timezone_set("Africa/Nairobi");

header('Content-Type:application/json');

// Retrieve session values safely
$temp_user = isset($_SESSION['is_temp_user']) ? $_SESSION['is_temp_user'] : false;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$token = isset($_SESSION['guest_token']) ? $_SESSION['guest_token'] : null;
$guest_continuem = isset($_SESSION['guest_continuem']) ? $_SESSION['guest_continuem'] : null;
$data[]=[
    "temp_user"=>$temp_user,
    "guest_continuem"=>$guest_continuem  
];
echo json_encode(["success" => true, "message" => $data]);
?>