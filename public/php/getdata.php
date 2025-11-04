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
$relationships = [];
// Retrieve session values safely
$is_temp_user = isset($_SESSION['is_temp_user']) ? $_SESSION['is_temp_user'] : false;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$guest_token = isset($_SESSION['guest_token']) ? $_SESSION['guest_token'] : null;

$memberTree = null;

$rank = "admin";

// Stop early if user_id is missing
if (!$user_id) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

if ($is_temp_user) {
    // Guest users
    $query = $con->prepare("SELECT treeLink FROM members WHERE memberUnid = ? AND rank = ? AND guest_token = ? LIMIT 1");
    $query->bind_param("sss", $user_id, $rank, $guest_token);
} else {
    // Permanent users
    $query = $con->prepare("SELECT treeLink FROM members WHERE memberUnid = ? AND rank = ? LIMIT 1");
    $query->bind_param("ss", $user_id, $rank);
}

$query->execute();
$result = $query->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $memberTree = $row['treeLink'];
    //echo json_encode(["success" => true, "treeId" => $memberTree]);
} else {
    exit();
   // echo json_encode(["success" => false, "message" => "No tree linked yet."]);
}



$stmt =$con->prepare("SELECT * FROM members WHERE treeId= ?");
$stmt ->bind_param("s",$memberTree);
$stmt->execute();
$results = $stmt->get_result();
while($row=$results->fetch_assoc()){
    $members[]=[
        "data"=>[
            "treeId" => $row["treeId"],
            "id" => $row["memberUnid"],
            "first_name" => $row["first_name"],
            "last_name" => $row["last_name"],
            "nickname" => $row["nickName"],
            "idNumber" => $row["idNumber"],
            "birthDate" => $row["birthDate"],
            "died" => $row["died"],
            "role" => $row["role"],
            "email" => $row["email"],
            "tel" => $row["tel"],
            "photo_webp" => $row["photo_webp"],
            "photo_jpg" => $row["photo_jpg"],
        ]
    ];
}
// get relationships
$rel = $con->prepare("SELECT source, target FROM relationships WHERE treeId = ?");
$rel->bind_param("s", $memberTree);
$rel->execute();
$relResults = $rel->get_result();
while($row = $relResults->fetch_assoc()) {
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