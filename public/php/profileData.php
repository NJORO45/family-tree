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

header('Content-Type:application/json');
include("db_connect.php");
include("functions.php");
$userUnid = $_SESSION['user_id'];
$userData=array();
$stmt= $con->prepare("SELECT * FROM members WHERE memberUnid =?");
$stmt->bind_param("s",$userUnid);
if($stmt->execute()){
    $results= $stmt->get_result();
    $data=$results->fetch_assoc();
    $userData[]=$data;
}
echo json_encode(["success"=>true,"messag"=>$userData]);
?>