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
//$data = json_decode(file_get_contents("php://input"), true);
// CSRF check
if ( !isset($_POST['csrtfToken']) || $_POST['csrtfToken'] !== $_SESSION['csrf_token']) {
    echo json_encode(["success" => false, "message" =>  "CSRF validation failed.". $_SESSION['csrf_token']]);
    exit;
}

if(isset($_POST['addNodeStatus']) && $_POST['addNodeStatus']==true){
    $treeId= "node". random_num(6);
    $memberUnid=  random_num(6);
    $name = sanitize($_POST['name']);
    $idNumber = sanitize($_POST['idNumber']);
    $birthDate = sanitize($_POST['birthDate']);
    $died = sanitize($_POST['died']);
    $nickName = sanitize($_POST['nickName']);
    $role = sanitize($_POST['role']);
    $uploadDir = "../../uploads/";
    $photoPathJPG = "";
    $photoPathWEBP = "";
    //  Handle file upload if exists
    /*
    if shows error Call to undefined function imagecreatefrompng()
    Here’s how to fix it:

    Open your PHP configuration file

    In XAMPP, it’s usually here:

    D:\program files\xamp\php\php.ini


    Find this line (it’s commented out by default):

    ;extension=gd


    Uncomment it (remove the ; at the beginning):

    extension=gd


    Restart Apache from your XAMPP control panel.
    (This step is required for changes to take effect.)
    */
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    $newFileName = uniqid("img_") . '.' . $ext;
    $photoPathJPG = $uploadDir . $newFileName;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPathJPG)) {
        //  Convert uploaded image to WebP
        $photoPathWEBP = $uploadDir . pathinfo($newFileName, PATHINFO_FILENAME) . ".webp";

        if (convertToWebP($photoPathJPG, $photoPathWEBP, 80)) {
            // Optional: you can remove the original if you only want the WebP
            // unlink($photoPath);
        } else {
            error_log(" WebP conversion failed for: $photoPathJPG");
        }
    } else {
        echo json_encode(["success" => false, "message" => "File upload failed"]);
        exit;
    }
}

    
    // //prepare insert data
    $stmt = $con->prepare("INSERT members (`treeId`, `memberUnid`, `name`, `idNumber`, `birthDate`, `died`, `nickName`,`role`,`photo_webp`, `photo_jpg`)
    VALUES
    (?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssssss",$treeId,$memberUnid,$name,$idNumber,$birthDate,$died,$nickName,$role,$photoPathWEBP,$photoPathJPG);
    if($stmt->execute()){
        echo json_encode(["success"=>true,"message"=>"success"]);
    }else{
        echo json_encode(["success"=>false,"message"=>"t"]);
    }
}
if(isset($_POST['addnewMemberStatus']) && $_POST['addnewMemberStatus']==true){
    $treeId= sanitize($_POST['connectionNode']);
    $connectionUnid= sanitize($_POST['connectionUnid']);
    $memberUnid=  random_num(6);
    $name = sanitize($_POST['newmembersname']);
    $idNumber = sanitize($_POST['newMemberidNumber']);
    $birthDate = sanitize($_POST['newMemberbirthDate']);
    $died = sanitize($_POST['newMemberdied']);
    $nickName = sanitize($_POST['newMembernickName']);
    $connectionContinumRelationship = sanitize($_POST['connectionContinumRelationship']);
    $connectionDirection = sanitize($_POST['connectionDirection']);
    $uploadDir = "../../uploads/";
    $photoPathJPG = "";
    $photoPathWEBP = "";

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    $newFileName = uniqid("img_") . '.' . $ext;
    $photoPathJPG = $uploadDir . $newFileName;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPathJPG)) {
        //  Convert uploaded image to WebP
        $photoPathWEBP = $uploadDir . pathinfo($newFileName, PATHINFO_FILENAME) . ".webp";

        if (convertToWebP($photoPathJPG, $photoPathWEBP, 80)) {
            // Optional: you can remove the original if you only want the WebP
            // unlink($photoPath);
        } else {
            error_log(" WebP conversion failed for: $photoPathJPG");
        }
    } else {
        echo json_encode(["success" => false, "message" => "File upload failed"]);
        exit;
    }
}

    //prepare insert data
    $stmt = $con->prepare("INSERT members (`treeId`, `memberUnid`, `name`, `idNumber`, `birthDate`, `died`, `nickName`,`role`,`photo_webp`, `photo_jpg`)
    VALUES
    (?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssssss",$treeId,$memberUnid,$name,$idNumber,$birthDate,$died,$nickName,$connectionContinumRelationship,$photoPathWEBP,$photoPathJPG);
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

//edit member data
if (isset($_POST['editmemberStatus']) && $_POST['editmemberStatus'] == "true") {
    $treeId     = sanitize($_POST['treeId']);
    $memberUnid = sanitize($_POST['userId']);
    $name       = sanitize($_POST['editname']);
    $idNumber   = sanitize($_POST['editidNumber']);
    $birthDate  = sanitize($_POST['editbirthDate']);
    $died       = sanitize($_POST['editdied']);
    $nickName   = sanitize($_POST['editnickName']);
    $role       = sanitize($_POST['editrole']);

    $uploadDir = "../../uploads/";
    $photoPathJPG = "";
    $photoPathWEBP = "";

    //  Check if photo file was uploaded
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $newFileName = uniqid("img_") . '.' . $ext;
        $photoPathJPG = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPathJPG)) {
            //  Convert uploaded image to WebP
            $photoPathWEBP = $uploadDir . pathinfo($newFileName, PATHINFO_FILENAME) . ".webp";

            if (!convertToWebP($photoPathJPG, $photoPathWEBP, 80)) {
                error_log("WebP conversion failed for: $photoPathJPG");
            }
        } else {
            echo json_encode(["success" => false, "message" => "File upload failed"]);
            exit;
        }

        // Update including new photo
        $stmt = $con->prepare("UPDATE members  SET name=?, idNumber=?, birthDate=?, died=?, nickName=?, role=?, photo_webp=?, photo_jpg=? WHERE memberUnid=? AND treeId=? ");
        $stmt->bind_param( "ssssssssss",$name, $idNumber, $birthDate, $died, $nickName, $role, $photoPathWEBP, $photoPathJPG, $memberUnid, $treeId);
    } else {
        //  Update without touching photo fields
        $stmt = $con->prepare("UPDATE members SET name=?, idNumber=?, birthDate=?, died=?, nickName=?, role=? WHERE memberUnid=? AND treeId=? ");
        $stmt->bind_param( "ssssssss", $name, $idNumber, $birthDate, $died, $nickName, $role, $memberUnid, $treeId);
    }

    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Member updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Database update failed"]);
    }
}

?>