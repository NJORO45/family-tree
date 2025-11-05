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
if(isset($_POST['guest_continuemStatus']) && $_POST['guest_continuemStatus'] ==true ){
    if (empty($_SESSION['guest_continuem'])) {
    $_SESSION['guest_continuem'] = bin2hex(random_bytes(32));
    echo json_encode(["success" => true, "message" => "Access allowed"]);
}
}
//create a session without login 
if(isset($_POST['continueWithoutLoginStatus']) && $_POST['continueWithoutLoginStatus'] ==true ){
    //create a session
    $_SESSION['user_id']= random_num(5);
    $_SESSION['guest_token'] = bin2hex(random_bytes(32));
    $_SESSION['is_temp_user'] = true; // mark as temporary
    $trmpUser = $_SESSION['is_temp_user'];
    $guestToken =$_SESSION['guest_token'];
    $userId=$_SESSION['user_id'];
    $treeId= "node". random_num(6);
    unset($_SESSION['guest_continuem']); 
    $rank ="admin";
    $stmt =$con->prepare("INSERT INTO members (`temp_user`, `memberUnid`,`guest_token`, `treeLink`, `rank`) VALUES(?,?,?,?,?)");
    $stmt->bind_param("sssss",$trmpUser,$userId,$guestToken,$treeId,$rank);
    if($stmt->execute()){
        echo json_encode(["success" => true, "message" => "temperary account created"]);
    }else{
        echo json_encode(["success" => false, "message" => "Error creating temperary account"]);
    }
    
}
if(isset($_POST['passwordResetStatus'])&& $_POST['passwordResetStatus']==true){
    $unid =  $_SESSION['user_id'];
    $oldpassword= sanitize($_POST['oldpassword']);
    $confirmnewpassword = sanitize($_POST['confirmnewpassword']);
   
    $stmt = $con->prepare("SELECT * FROM members WHERE `memberUnid` = ? LIMIT 1");
    $stmt->bind_param("s",$unid);
    if($stmt->execute()){
        $results = $stmt->get_result();
        $user=$results->fetch_assoc();
        if($user && password_verify($oldpassword,$user['passwordHash'])){
            $hashedPassword = password_hash($confirmnewpassword,PASSWORD_DEFAULT);
            $updatetData = $con->prepare("UPDATE `members` SET `passwordHash`= ?
            WHERE `memberUnid`= ?");
            $updatetData->bind_param("ss",$hashedPassword,$unid);
            if($updatetData->execute()){
                echo json_encode(["success" => true, "message" => "Password updated"]); 
                //send email to user to tell them that the password was changed if it wosnt then to block the action
            }else{
                echo json_encode(["success" => false, "message" => "error accured when updating password"]); 
            }
        }else{
            echo json_encode(["success" => false, "message" => "wrong old password"]);
        }
    }else{
        echo json_encode(["success" => false, "message" => "Database error"]);
    }
}
if(isset($_POST['profileUpdate'])&& $_POST['profileUpdate']==true){
    $unid =  $_SESSION['user_id'];
$Fname = sanitize($_POST['first_name']);
$Lname = sanitize($_POST['last_name']);
$email = sanitize($_POST['email']);
$tel = sanitize($_POST['tel']);
//estro sertain setions 
$_SESSION['guest_token'] = "";
$_SESSION['is_temp_user'] = "false"; // mark as temporary 
unset($_SESSION['guest_continuem']); 
    $tempPassword = password_hash("0", PASSWORD_DEFAULT); // hashed temporary password
    $insertData = $con->prepare("UPDATE `members` SET 
    `temp_user`=?,`guest_token`=?,`first_name`= ?,`last_name`= ?,`email`= ?,`tel`= ?,`passwordHash`=?
     WHERE `memberUnid`= ?");
     $trmpUser = $_SESSION['is_temp_user'];
    $guestToken =$_SESSION['guest_token'];
    $insertData->bind_param("ssssssss",$trmpUser,$guestToken,$Fname,$Lname,$email,$tel,$tempPassword,$unid);
    if($insertData->execute()){
        echo json_encode(["success" => true, "message" => "Profile updated"]); 
    }else{
        echo json_encode(["success" => false, "message" => "error accured when updating profile"]); 
    }
}
if(isset($_POST['addNodeStatus']) && $_POST['addNodeStatus']==true){
    //get node link from admin
    $userId=$_SESSION['user_id'];
    $rank ="admin";
    $adminQuery = $con->prepare("SELECT treeLink FROM members WHERE memberUnid= ? AND rank =?");
    $adminQuery->bind_param("ss",$userId,$rank);
    $adminQuery->execute();
    $adminResults =$adminQuery->get_result();
    $adminData= $adminResults ->fetch_assoc();
    $treeId= $adminData['treeLink'];
    $memberUnid=  random_num(6);
    $fname = sanitize($_POST['fname']);
    $lname = sanitize($_POST['lname']);
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
    $stmt = $con->prepare("INSERT members (`treeId`, `memberUnid`, `first_name`,`last_name`, `idNumber`, `birthDate`, `died`, `nickName`,`role`,`photo_webp`, `photo_jpg`)
    VALUES
    (?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssssssss",$treeId,$memberUnid,$fname,$lname,$idNumber,$birthDate,$died,$nickName,$role,$photoPathWEBP,$photoPathJPG);
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
    $newmembersfname = sanitize($_POST['newmembersfname']);
    $newmemberslname = sanitize($_POST['newmemberslname']);
    $idNumber = sanitize($_POST['newMemberidNumber']);
    $birthDate = sanitize($_POST['newMemberbirthDate']);
    $died = sanitize($_POST['newMemberdied']);
    $nickName = sanitize($_POST['newMembernickName']);
    $connectionContinumRelationship = sanitize($_POST['connectionContinumRelationship']);
    $connectionDirection = sanitize($_POST['connectionDirection']);
    $uploadDir = "../../uploads/";
    $photoPathJPG = "";
    $photoPathWEBP = "";
    $rank ="user";
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
    $stmt = $con->prepare("INSERT members (`treeId`, `memberUnid`, `first_name`,`last_name`, `idNumber`, `birthDate`, `died`, `nickName`,`role`,`rank`,`photo_webp`, `photo_jpg`) 
    VALUES
    (?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssssssss",$treeId,$memberUnid,$newmembersfname,$newmemberslname,$idNumber,$birthDate,$died,$nickName,$connectionContinumRelationship,$rank,$photoPathWEBP,$photoPathJPG);
    if($stmt->execute()){
        //echo json_encode(["success"=>true,"message"=>"success"]);
        //check if connetion is forawd or backwardss
        //   //relationship
      //   { data: { source: 'same', target: 'mary' }},
      if($connectionDirection == "forward"){
        $stmt = $con->prepare("INSERT relationships (`source`, `target`,`treeId`)
        VALUES
        (?,?,?)");
        $stmt->bind_param("sss",$connectionUnid,$memberUnid,$treeId);
        if($stmt->execute()){
            echo json_encode(["success"=>true,"message"=>"success"]);
        }
      }else if($connectionDirection == "backward"){
        $stmt = $con->prepare("INSERT relationships (`source`, `target`,`treeId`)
        VALUES
        (?,?,?)");
        $stmt->bind_param("sss",$memberUnid,$connectionUnid,$treeId);
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
    $editfname  = sanitize($_POST['editfname']);
    $editlname  = sanitize($_POST['editlname']);
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
        $stmt = $con->prepare("UPDATE members  SET first_name=?, last_name=?, idNumber=?, birthDate=?, died=?, nickName=?, role=?, photo_webp=?, photo_jpg=? WHERE memberUnid=? AND treeId=? ");
        $stmt->bind_param( "sssssssssss",$editfname,$editlname, $idNumber, $birthDate, $died, $nickName, $role, $photoPathWEBP, $photoPathJPG, $memberUnid, $treeId);
    } else {
        //  Update without touching photo fields
        $stmt = $con->prepare("UPDATE members SET first_name=?, last_name=?, idNumber=?, birthDate=?, died=?, nickName=?, role=? WHERE memberUnid=? AND treeId=? ");
        $stmt->bind_param( "sssssssss", $editfname,$editlname, $idNumber, $birthDate, $died, $nickName, $role, $memberUnid, $treeId);
    }

    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Member updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Database update failed"]);
    }
}

if(isset($_POST['removememberStatus']) && $_POST['removememberStatus']==true){
    $memberUnid = sanitize($_POST['removeMemberId']);
    $message="";
    $response = [
        "deleted_as_target" => false,
        "deleted_as_source" => false,
        "message" => ""
    ];
    $stmt = $con->prepare("DELETE FROM members WHERE memberUnid= ?");
        $stmt->bind_param( "s", $memberUnid);
        if ($stmt->execute()) {
        // Delete where member is target
        $deleteTarget = $con->prepare("DELETE FROM relationships WHERE target = ?");
        $deleteTarget->bind_param("s", $memberUnid);
        if ($deleteTarget->execute()) {
            if ($deleteTarget->affected_rows > 0) {
                $response["deleted_as_target"] = true;
            }
        }

        //Delete where member is source
        $deleteSource = $con->prepare("DELETE FROM relationships WHERE source = ?");
        $deleteSource->bind_param("s", $memberUnid);
        if ($deleteSource->execute()) {
            if ($deleteSource->affected_rows > 0) {
                $response["deleted_as_source"] = true;
            }
        }
        if ($response["deleted_as_target"] && $response["deleted_as_source"]) {
            $response["message"] = "Member removed from both source and target relationships.";
        } elseif ($response["deleted_as_target"]) {
            $response["message"] = "Member removed as target.";
        } elseif ($response["deleted_as_source"]) {
            $response["message"] = "Member removed as source.";
        } else {
            $response["message"] = "No relationships found for this member.";
        }
        echo json_encode(["success" => true, "message" => "Member and related connections deleted successfully.","details"=>$response]);
    } else {
        echo json_encode(["success" => false, "message" => "failed to delete from database"]);
    }
}
//login loginStatus
if(isset($_POST['loginStatus']) && $_POST['loginStatus'] == "true"){
$loginemail       = sanitize($_POST['loginemail']);
$logintel       = sanitize($_POST['logintel']);
$loginpassword       = sanitize($_POST['loginpassword']);

if (empty($loginemail) && empty($logintel)) {
        echo json_encode(["success" => false, "message" => "Email or phone required."]);
        exit;
    }

    // Determine login method
    if (!empty($loginemail)) {
        $stmt = $con->prepare("SELECT * FROM members WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $loginemail);
    } else {
        $stmt = $con->prepare("SELECT * FROM members WHERE tel = ? LIMIT 1");
        $stmt->bind_param("s", $logintel);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($loginpassword, $user['passwordHash'])) {
            $_SESSION['user_id'] = $user['memberUnid'];
            $_SESSION['is_temp_user'] = "false";

            // Check if this user is an admin
            if ($user['rank'] === 'admin') {
                $_SESSION['user_id'] = $user['memberUnid'];
                $_SESSION['is_admin'] = "true";
                $_SESSION['is_temp_user'] = "false";
                $_SESSION['treeLink'] = $user['treeLink']; // assume each admin owns a tree
                echo json_encode(["success" => true, "message" => "Admin login successful", "role" => $_SESSION['is_admin']]);
                exit;
            }

            // Check if this user also exists as a members in any tree
            $memberCheck = $con->prepare("SELECT * FROM members WHERE email = ? or tel=? LIMIT 1");
            $memberCheck->bind_param("ss", $loginemail, $logintel);
            $memberCheck->execute();
            $memberResult = $memberCheck->get_result();

            if ($memberResult->num_rows >0) {
                $member = $memberResult->fetch_assoc();
                $_SESSION['user_id'] = $member['memberUnid'];
                $_SESSION['is_admin'] = "false";
                $_SESSION['is_temp_user'] = "false";
                $_SESSION['treeLink'] = $member['treeId'];
                echo json_encode(["success" => true, "message" => "Member login successful", "role" => "member"]);
            } else {
                echo json_encode(["success" => false, "message" => "User found but not linked to a tree ","email"=>$loginemail ]);
            }

        } else {
            echo json_encode(["success" => false, "message" => "Incorrect password"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "User not found"]);
    }
}
//send otp to email
if(isset($_POST['passwordResetotpStatus']) && $_POST['passwordResetotpStatus'] == "true"){
    $email       = sanitize($_POST['email']);
    $tel       = sanitize($_POST['tel']);
    //
    if (empty($email) && empty($tel)) {
        echo json_encode(["success" => false, "message" => "Email or phone required."]);
        exit;
    }

    // Determine login method
    if (!empty($email)) {
        $stmt = $con->prepare("SELECT * FROM members WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
    } else {
        $stmt = $con->prepare("SELECT * FROM members WHERE tel = ? LIMIT 1");
        $stmt->bind_param("s", $tel);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $member = $result->fetch_assoc();

        //create otp
        $otp= random_num(4);
        $otpvalidationToken = bin2hex(random_bytes(32));
        $expiry = new DateTime();
        $expiry->modify('+2 hours');
        $expiryDate = $expiry->format('Y-m-d H:i:s');
        //update to database
        $user = $member['memberUnid'];
        $updateQuery = $con->prepare("UPDATE members SET OTP =?, otpvalidationToken =?, otpExpiryDate =?  WHERE memberUnid = ?");
        $updateQuery->bind_param("ssss",$otp,$otpvalidationToken,$expiryDate,$user);
        if($updateQuery->execute()){
            //send email
            sendEmail($email,$otpvalidationToken,$otp);
            echo json_encode(["success" => true, "message" => "OTP sent succesfully","otpvalidationToken"=>$otpvalidationToken]);
        }
    } else {
    echo json_encode(["success" => false, "message" => "User not found"]);
    }
}
if(isset($_POST['validateotpStatus']) && $_POST['validateotpStatus'] == "true"){
    $otpInput = sanitize($_POST['otpInput']);
    $userId = $_SESSION['user_id'];
    $stmt = $con->prepare("SELECT * FROM members WHERE memberUnid = ?  LIMIT 1");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $stmtResult = $stmt->get_result();

    if ($stmtResult->num_rows === 1) {
        $data = $stmtResult->fetch_assoc();
        $otp = $data['OTP'];
        $otpExpiryDate = $data['otpExpiryDate'];
        $currentTime = date("Y-m-d H:i:s");

        // Check if OTP matches
        if ($otpInput === $otp) {
            // Check if OTP is expired
            if ($currentTime <= $otpExpiryDate) {
                echo json_encode([
                    "success" => true,
                    "message" => "OTP is valid. You can now reset your password."
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "OTP has expired. Please request a new one."
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Invalid OTP entered."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "User not found or invalid session."
        ]);
    }
}
if(isset($_POST['updatePasswordStatus']) && $_POST['updatePasswordStatus'] == "true"){
    $newPassword = sanitize($_POST['newPassword']);
    $userId = $_SESSION['user_id'];
    $passwordHash = password_hash($newPassword,PASSWORD_DEFAULT);
    $otp = ""; // empty string
    $otpvalidationToken = ""; // empty string
    $expiryDate = null; // null for date/time columns
    //update
    $updateQuery = $con->prepare("UPDATE members SET OTP =?, otpvalidationToken =?, otpExpiryDate =?, passwordHash =?  WHERE memberUnid = ?");
        $updateQuery->bind_param("sssss",$otp,$otpvalidationToken,$expiryDate,$passwordHash,$userId);
        if($updateQuery->execute()){
            echo json_encode(["success" => true, "message" => "Password Updated succesfully"]);
            // Unset all session variables
            // $_SESSION = [];
            // // Destroy the session
            // session_destroy();
        }else{
            echo json_encode(["success" => true, "message" => "Error Occured While Updating Password"]);
        }
}
?>