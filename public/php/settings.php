<?php
session_start();
$temp_user = $_SESSION['is_temp_user'];
if (isset($_SESSION['user_id'])) {
   
} else {
    header("Location:../index.html");
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
      <script src="https://cdnjs.cloudflare.com/ajax/libs/cytoscape/3.24.0/cytoscape.min.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
   
    .card {
      position: absolute;
      transform: translate(-50%, -50%);
      pointer-events: auto; /* let you click buttons */
      width: 200px;
      transition: transform 0.1s ease-out;
    }
  </style>
</head>
<body class="overflow-auto flex flex-col">
    <!--alert message-->
   <div id="alertMessage" class="fixed z-[60] left-1/2 -translate-x-1/2 bg-red hidden bg-orange-300 mt-2 rounded-lg shadow-xl px-3 py-1 gap-1 ">
       <i class="ri-error-warning-fill text-xl"></i>
       <p>alert message</p>
   </div>
   <!-- Top Navigation Bar -->
<nav class="w-full bg-white shadow-md px-2 md:px-6 py-3 flex justify-between gap-1 items-center fixed z-20">

  <!-- Left: Project Name or Logo -->
  <a href="dashboard.php" class="text-sm md:text-2xl font-semibold text-green-600">
    FamilyTree<span class="text-gray-600">.io</span>
  </a>

  <!-- Middle: Search -->
  <div class="flex items-center w-1/3">
    <input type="text" placeholder="Search by name or nickname..."
           class="w-full border border-gray-300 rounded-full px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400">
  </div>
<!-- Add Tree Button -->
<button class="ml-4">
  <i id="addnewNode" class="ri-add-line text-xl  bg-green-500 hover:bg-green-400 text-white rounded-full  shadow-lg"></i>
</button>

  <!-- Right: User Menu -->
  <div class="relative" id="userMenu">
    <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none ">
        <i class="ri-user-line w-10 h-10 rounded-full border-2 border-green-400 flex items-center justify-center text-lg"></i>
      
      <?php
      if($temp_user==true){
        echo '<span id="userName" class="hidden md:block text-gray-700 font-medium">Guest</span>';
      }
      ?>
    </button>

    <!-- Dropdown Menu -->
    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100">
      <a href="profile.php" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Profile</a>
      <a href="#" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Settings</a>
      <div class="border-t border-gray-200 my-1"></div>
      <a id="logoutBtn" href="#" class="block px-4 py-2 text-green-600 hover:bg-gray-100">Logout</a>
    </div>
    <img src="/img/one.jfif" alt="">
  </div>
</nav>
   <!--firstsection-->
   <div class="flex-grow pt-20 grid grid-cols-1 sm:grid-cols-1 place-items-center px-4 space-y-2 border-b-2 pb-4">
    <div class=" flex flex-col space-y-2 shadow-lg rounded-lg p-2">
        <!--personal details-->
        <h2 class="text-lg text-center font-bold mb-2">Account Security</h2>
        <input id="csrtftokenpaswordReset" class="csrtfToken" type="text" value="" hidden >
        <div class="flex flex-col sm:flex-row gap-2 justify-between">
            <label for="">Old password</label>
            <input id="oldPassword" class="border-2 rounded-lg outline-none px-2 py-1" placeholder="if no password use 0" type="password">
            <p id="oldpassError" class="text-green-500 text-xs font-semibold"></p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 justify-between">
            <label for="">New password</label>
            <input id="newPassword" class="border-2 rounded-lg outline-none px-2 py-1" type="password">
            <p id="newpassError" class="text-green-500 text-xs font-semibold"></p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 justify-between">
            <label for="">Confirm New Password</label>
            <input id="ConfirmNewPassword" class=" border-2 rounded-lg outline-none px-2 py-1" type="password">
            <p id="confirmpassError" class="text-green-500 text-xs font-semibold"></p>
        </div>
        <div class="flex justify-center">
            <button id="changePasswordBtn" class="bg-green-400 px-2 py-1 text-white rounded-full text-sm hover:bg-green-600">Change Password</button>
        </div>
    </div>
    <div class="flex flex-col space-y-2 shadow-lg rounded-lg p-2">
        <h2 class="text-lg font-bold text-center">Preferences</h2>
        <div>
            <h1>Notification Preferences (SMS/email reminders)</h1>
            <div>
                <input id="csrtftokenpreference" class="csrtfToken" type="text" value="" hidden >
                <div>
                    <input type="radio" id="Selectedsms" name="notification" value="sms">
                    <label for="SMS" >SMS</label>
                </div>
                <div>
                    <input type="radio" id="Selectedemail" name="notification" value="email">
                    <label for="Email" >Email</label>
                </div>
            </div>
            <div class="flex justify-center">
            <button id="saveOptionBtn" class="bg-green-400 px-2 py-1 text-white rounded-full text-sm hover:bg-green-600">Save option</button>
        </div>
        </div>
    </div>
    <div class="flex flex-col space-y-2 shadow-lg rounded-lg p-2">
        <h2 class="text-lg text-center font-bold">Danger Zone</h2>
        <div class="text-green-500 p-4 hover:text-green-800">
           <button id="deleteAccountBtn">Deactivate/Delete Account</button>
        </div>
    </div>
    </div>
    <!--back to top-->
<div id="backToTopBtn" class="hidden fixed bottom-6 right-6 bg-green-400 shadow-xl rounded-full z-30  w-8 h-8 flex justify-center items-center cursor-pointer hover:bg-green-600 transition duration-100 ease-in-out">
    <i class="ri-arrow-up-double-fill text-xl text-white"></i>
</div>
<div id="deactivateAccount" class="fixed z-40 inset-0 bg-black/50 hidden  justify-center  h-full pb-2 overflow-y-auto">
    <div class="bg-white rounded-xl w-full max-w-md md:max-w-lg h-fit mt-20 px-4 py-2">
        <div class="w-full flex justify-end">
            <i id="cross" class="ri-close-large-line cursor-pointer inline-block text-xl text-orange-400 transform transition duration-200 hover:scale-125 hover:text-orange-600"></i>
        </div>
        <h2 class="mb-2 font-semibold text-center text-lg">Deactivate/Delete Account</h2>
        <div class="space-y-2">
            <div>
                <p class="text-red-500 text-center py-4">Are you sure you want to delete/deactivate your account</p>
            </div>
        </div>
        <div class="flex flex-col py-2">
            <button id="deactivateTrue" class="bg-green-400 px-2 py-2 rounded-full text-white hover:bg-green-600">Yes</button>
        </div>
    </div>
</div>

 <script type="module" src="../js/csrf.js"></script>
 <script type="module" src="../js/main.js"></script>
 <script type="module"  src="../js/settings.js"></script>
 <script type="module"  src="../js/logout.js"></script>
</body>
</html>