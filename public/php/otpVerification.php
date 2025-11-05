<?php
session_start();
include("db_connect.php");
$expiryState='';
if (isset($_GET['token'])&& !empty($_GET['token'])) {
   // Look for user with this token
   
   $token=$_GET['token'];
    $stmt = $con->prepare("SELECT * FROM members WHERE otpvalidationToken = ? AND otpExpiryDate > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['memberUnid']; // store user ID in session
        $expiryState='false';
    } else {
        $expiryState='true';
        
    }

} else {
    header("Location:resetPassword.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../main.css">
    <!--favicon -->
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css"  />
</head>
<body>
    <!--alert message-->
   <div id="alertMessage" class="fixed z-50 left-1/2 -translate-x-1/2 bg-red hidden bg-orange-300 mt-2 rounded-lg shadow-xl px-3 py-1 gap-1 ">
       <i class="ri-error-warning-fill text-xl"></i>
       <p>alert message</p>
   </div>
   <input type="hidden" id="expiryStateid"  value="<?php echo $expiryState?>">
    <div id="newPasswordFoem" class="hidden w-full h-screen flex overflow-hidden justify-center items-start pt-20 bg-gray-50">
        <div class="flex flex-col space-y-6 bg-white shadow-lg rounded-lg p-8 max-w-sm w-full">
            <div class="flex flex-row justify-center gap-2  px-2 py-1 rounded-sm">
                <p class="">Set New Password</p>
            </div> 
            <div class="flex flex-col relative">
                <label for="password" class="font-medium text-gray-700">New Password</label>
                <input type="password" id="newPassword" class="border-2 border-green-300 outline-none rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400">
                <i id="eyeIcon1" class="ri-eye-fill text-2xl absolute right-2 top-8 cursor-pointer"></i>
            </div>
            <div class="flex flex-col relative">
                <label for="password" class="font-medium text-gray-700">Confirm Password</label>
                <input type="password" id="ConfirmPassword" class="border-2 border-green-300 outline-none rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400">
                <i id="eyeIcon" class="ri-eye-fill text-2xl absolute right-2 top-8 cursor-pointer"></i>
                <p id="confirmPasswordError" class="text-red-400 font-bold "></p>
            </div>
                <input type="hidden" id="csrtfTokenid" class="csrtfToken" id="">
            <div class="flex flex-col gap-2">
                <button id="updatePasswordBtn" class="bg-green-500 px-4 py-2 rounded-lg shadow-lg text-white text-xl font-semibold hover:bg-green-400 transition">Update Password</button>
                
            </div>
            
        </div>
    </div>
    <!-- otp verification -->
      <div class="w-full h-screen flex overflow-hidden justify-center items-start pt-20 bg-gray-50">
        <div class="flex flex-col space-y-6 bg-white shadow-lg rounded-lg p-8 max-w-sm w-full">
            <div class="flex flex-row justify-center gap-2  px-2 py-1 rounded-sm">
                <p class="">Verify OTP</p>
            </div>    
            
            <div class="flex flex-col">
                <input id="otpInput" type="text" class="border-2 border-green-300 outline-none rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400" placeholder=" OTP">
            </div>
             <input type="hidden" id="csrtfTokenid" class="csrtfToken" id="">
            <div class="flex flex-col gap-2">
                <button id="verifyOtpBtn" class="bg-green-500 px-4 py-2 rounded-lg shadow-lg text-white text-xl font-semibold hover:bg-green-400 transition">Verify OTP</button>
                
            </div>
        </div>
    </div>
</body>
 <script type="module"  src="../js/verifyOtp.js"></script>
 <script type="module"  src="../js/csrf.js"></script>
 <script type="module"  src="../js/updatePassword.js"></script>
</html>