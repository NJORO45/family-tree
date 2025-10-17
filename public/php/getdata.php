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
include("db_connect.php");
include("functions.php");
//get members
$members=[];
$stmt =$con->prepare("SELECT * FROM members");
$stmt->execute();
$results = $stmt->get_result();
while($row=$results->fetch_assoc()){
    $members[]=[
        "data"=>[
            "treeId" => $row["treeId"],
            "id" => $row["memberUnid"],
            "name" => $row["name"],
            "nickname" => $row["nickName"],
            "idNumber" => $row["idNumber"],
            "birthDate" => $row["birthDate"],
            "died" => $row["died"],
            "role" => $row["role"],
            "photo_webp" => $row["photo_webp"],
            "photo_jpg" => $row["photo_jpg"],
        ]
    ];
}
// get relationships
$relationships = [];
$result = $con->query("SELECT * FROM relationships");
while($row = $result->fetch_assoc()) {
    $relationships[] = [
        "data" => [
            "source" => $row["source"],
            "target" => $row["target"]
        ]
    ];
}

// combine
$response = array_merge($members, $relationships);

echo json_encode($response);
?>