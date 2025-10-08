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
<body class="overflow-hidden">
    <!--alert message-->
   <div id="alertMessage" class="fixed z-50 left-1/2 -translate-x-1/2 bg-red hidden bg-orange-300 mt-2 rounded-lg shadow-xl px-3 py-1 gap-1 ">
       <i class="ri-error-warning-fill text-xl"></i>
       <p>alert message</p>
   </div>
   <div>
    
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
  <i class="ri-add-line text-xl  bg-green-500 hover:bg-green-400 text-white rounded-full  shadow-lg"></i>
</button>

  <!-- Right: User Menu -->
  <div class="relative" id="userMenu">
    <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none ">
        <i class="ri-user-line w-10 h-10 rounded-full border-2 border-green-400 flex items-center justify-center text-lg"></i>
      <span class="hidden md:block text-gray-700 font-medium">John</span>
    </button>

    <!-- Dropdown Menu -->
    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100">
      <a href="#" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Profile</a>
      <a href="#" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Settings</a>
      <div class="border-t border-gray-200 my-1"></div>
      <a href="#" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Logout</a>
    </div>
    <img src="/img/one.jfif" alt="">
  </div>
</nav>
<!--node area-->
<div id="family-tree-area" class="absolute top-0 left-0 w-full h-full z-10 bg-slate-100"></div>



<div id="details" class="fixed hidden top-1/3 left-1/2 -translate-x-1/2 z-50 max-w-lg bg-slate-100  rounded-lg shadow-lg p-4">
      
</div>
 <script src="../js/chart.js"></script>
 <script src="../js/main.js"></script>
</body>
</html>