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
$data = json_decode(file_get_contents("php://input"), true);
// CSRF check
if (!$data || !isset($data['csrtfToken']) || $data['csrtfToken'] !== $_SESSION['csrf_token']) {
    echo json_encode(["success" => false, "message" => !$data ? "Invalid JSON" : "CSRF validation failed" . $_SESSION['csrf_token']]);
    exit;
}

if(isset($data['addNodeStatus']) && $data['addNodeStatus']==true){
    $treeId= "node". random_num(6);
    $memberUnid=  random_num(6);
    $name = sanitize($data['name']);
    $idNumber = sanitize($data['idNumber']);
    $birthDate = sanitize($data['birthDate']);
    $died = sanitize($data['died']);
    $nickName = sanitize($data['nickName']);
    
    //prepare insert data
    $stmt = $con->prepare("INSERT members (`treeId`, `memberUnid`, `name`, `idNumber`, `birthDate`, `died`, `nickName`)
    VALUES
    (?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssss",$treeId,$memberUnid,$name,$idNumber,$birthDate,$died,$nickName);
    if($stmt->execute()){
        echo json_encode(["success"=>true,"message"=>"success"]);
    }else{
        echo json_encode(["success"=>false,"message"=>"t"]);
    }
}
if(isset($data['addnewMemberStatus']) && $data['addnewMemberStatus']==true){
    $treeId= sanitize($data['connectionNode']);
    $connectionUnid= sanitize($data['connectionUnid']);
    $memberUnid=  random_num(6);
    $name = sanitize($data['newmembersname']);
    $idNumber = sanitize($data['newMemberidNumber']);
    $birthDate = sanitize($data['newMemberbirthDate']);
    $died = sanitize($data['newMemberdied']);
    $nickName = sanitize($data['newMembernickName']);
    $connectionContinumRelationship = sanitize($data['connectionContinumRelationship']);
    $connectionDirection = sanitize($data['connectionDirection']);
    
    //prepare insert data
    $stmt = $con->prepare("INSERT members (`treeId`, `memberUnid`, `name`, `idNumber`, `birthDate`, `died`, `nickName`,`role`)
    VALUES
    (?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssss",$treeId,$memberUnid,$name,$idNumber,$birthDate,$died,$nickName,$connectionContinumRelationship);
    if($stmt->execute()){
        //echo json_encode(["success"=>true,"message"=>"success"]);
        //check if connetion is forawd or backwards
        //   //relationship
      //   { data: { source: 'same', target: 'mary' }},
      if($connectionDirection == "forward"){
        $stmt = $con->prepare("INSERT relationships (`source`, `target`)
        VALUES
        (?,?)");
        $stmt->bind_param("ss",$connectionUnid,$memberUnid);
        if($stmt->execute()){
            echo json_encode(["success"=>true,"message"=>"success"]);
        }
      }else if($connectionDirection == "backward"){
        $stmt = $con->prepare("INSERT relationships (`source`, `target`)
        VALUES
        (?,?)");
        $stmt->bind_param("ss",$memberUnid,$connectionUnid);
        if($stmt->execute()){
            echo json_encode(["success"=>true,"message"=>"success"]);
        }
      }

    }else{
        echo json_encode(["success"=>false,"message"=>"t"]);
    }
}
?>