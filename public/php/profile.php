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
  <div class="text-sm md:text-2xl font-semibold text-green-600">
    FamilyTree<span class="text-gray-600">.io</span>
  </div>

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
      <a href="#" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Profile</a>
      <a href="settings.php" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Settings</a>
      <div class="border-t border-gray-200 my-1"></div>
      <a id="logoutBtn" href="#" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Logout</a>
    </div>
    <img src="/img/one.jfif" alt="">
  </div>
</nav>
<!--node area-->
   <!--firstsection-->
   <div class="flex-grow pt-20 flex flex-col place-items-center px-4 space-y-2 pb-4">
    <div class="flex flex-col space-y-2 shadow-lg rounded-lg p-2">
        <!--personal details-->
        <h2 class="text-sm font-bold mb-2 text-center">Personal Info</h2>
        <input id="csrtftokenProfile" class="csrtfToken" type="text" value="" hidden >
        <div class="flex flex-col sm:flex-row justify-between gap-2">
            <label for=""> First name</label>
            <input id="Fname" class="border-2 rounded-lg outline-none px-2 py-1" type="text">
        </div>
        <div class="flex flex-col sm:flex-row justify-between gap-2">
            <label for=""> Last name</label>
            <input id="Lname" class="border-2 rounded-lg outline-none px-2 py-1" type="text">
        </div>
        <div class="flex flex-col sm:flex-row justify-between gap-2">
            <label for="">Tel</label>
            <input id="tel" class="border-2 rounded-lg outline-none px-2 py-1" type="text">
        </div>
        <div class="flex flex-col sm:flex-row justify-between gap-2">
            <label for="">Email</label>
            <input id="email" class="border-2 rounded-lg outline-none px-2 py-1 " type="text">
        </div>
        <div class="flex justify-center">
            <button id="saveBtn" class="bg-green-400 px-2 py-1 text-white rounded-full text-sm hover:bg-green-600">Save changes</button>
        </div>
    </div>
    <div class="flex flex-col space-y-2 shadow-lg rounded-lg p-2">
        <h2 class="text-sm font-bold">Other Details</h2>
        <div class="flex flex-col gap-2">
            <label for="" class="text-gray-900">📅 Joined Date</label>
            <p id="joinDate" class="text-gray-600 text-sm">2025/08/16 14:40:09</p>
        </div>
        <!-- <div>
            <label for="" class="text-gray-900">💳 Subscription</label>
            <p id="subscriptionPlan" class="text-gray-600 text-sm">N/A</p>

        </div>
        <div>
            <label for="" class="text-gray-900">Subscription Status</label>
            <p class="space-y-2">
                <p class="text-gray-600  text-sm ">Next payment Due</p>
                <div id="planExpiryDate" class="text-gray-600  text-sm ">N/A</div>
            </div>
    </div> -->
    </div>
</div>

 <script type="module" src="../js/csrf.js"></script>
 <script src="../js/main.js"></script>
 <script type="module"  src="../js/profile.js"></script>
 <script type="module"  src="../js/logout.js"></script>
</body>
</html>