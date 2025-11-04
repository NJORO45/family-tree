<?php
session_start();

if (isset($_SESSION['user_id'])) {
   
} else {
    header("Location:../index.html");
    exit;
}
$temp_user = $_SESSION['is_temp_user'];
$rankStatus = $_SESSION['is_admin'];
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
<body class="overflow-hidden">
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
    <input id="rankState" type="hidden" value="<?php echo $rankStatus?>">
    <!-- Dropdown Menu -->
    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100">
      <a href="profile.php" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Profile</a>
      <a href="settings.php" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Settings</a>
      <div class="border-t border-gray-200 my-1"></div>
      <a id="logoutBtn" href="#" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Logout</a>
    </div>
    <img src="/img/one.jfif" alt="">
  </div>
</nav>
<!--node area-->
<div id="family-tree-area" class="absolute top-0 left-0 w-full h-full z-10 bg-slate-100"></div>



<div id="detailscontainer" class="fixed hidden   flex flex-col justify-center w-full h-screen items-center z-50 overflow-y-auto">
  <div id="details" class=" hidden  flex flex-col justify-center w-full z-50 max-w-sm md:max-w-md  bg-slate-100  rounded-lg shadow-lg overflow-y-auto">   
</div>
</div>
<div id="newNodeData" class="hidden fixed flex flex-col justify-center items-center  z-50  w-full h-screen p-4">
  <input type="hidden" id="csrtfTokenid" class="csrtfToken" id="">
      <div class="flex flex-col  max-w-md w-full  bg-slate-100  rounded-lg shadow-lg p-4">
        <div class="flex flex-row space-y-2 ">
          <h2 class=" font-bold flex justify-end w-2/3 pr-4 text-xl">Family tree</h2>
          <i id="closeNodedata" class="ri-close-large-fill flex justify-end w-1/3 text-green-400 font-bold text-2xl hover:scale-105"></i>
        </div>
        <div class="flex flex-col gap-2">
          <label for="img">Photo</label>
          <input type="file" id="memberPhoto" name="img" accept="image/jpeg, image/png, image/jpg" class="border rounded p-2">
          <img id="preview" class="w-32 h-32 object-cover rounded hidden" alt="Preview">
        </div>
        <div class="flex flex-col gap-2">
          <label for="img">First Name</label>
          <input id="fname" type="text" placeholder="Full name" class="px-2 py-1 rounded-lg outline-none border">
        </div>
        <div class="flex flex-col gap-2">
          <label for="img">Last Name</label>
          <input id="lname" type="text" placeholder="Full name" class="px-2 py-1 rounded-lg outline-none border">
        </div>
        <div class="flex flex-col gap-2">
          <label for="img">ID</label>
          <input id="idNumber" type="text" placeholder="Full name" class="px-2 py-1 rounded-lg outline-none border">
        </div>
        <div class="flex flex-col gap-2">
          <label for="img">Born (Sunrise)</label>
          <input id="birthDate" type="date"  class="px-2 py-1 rounded-lg outline-none border">
        </div>
          <!-- Deceased toggle -->
      <div class="flex items-center gap-2 mb-2">
        <input type="checkbox" id="isDeceased" class="w-4 h-4 text-green-500 border-gray-300 rounded">
        <label for="isDeceased" class="text-gray-700">Deceased</label>
      </div>

      <!-- Date of death (hidden by default) -->
      <div id="deathContainer" class="flex flex-col gap-2 mb-2 hidden">
        <label for="died">Passed Away (Sunset)</label>
        <input type="date" id="died" class="px-2 py-1 rounded-lg outline-none border">
      </div>
      <div class="flex flex-col gap-2">
          <label for="img">nick name</label>
          <input id="nickName" type="text" placeholder="mama or baba ann" class="px-2 py-1 rounded-lg outline-none border">
        </div>
         <div class="flex flex-col gap-2">
          <label for="img">Role</label>
          <input id="role" type="text" placeholder="Father, Mother or Child" class="px-2 py-1 rounded-lg outline-none border">
        </div>
        <div class="flex flex-col gap-2 m-4  text-center  mx-auto">
          <button id="addNewNodeBtn" class="bg-green-400 px-2 py-1 rounded-lg w-max text-white mx-auto">Add</button>
        </div>
    </div>
        
  </div>
<div id="newmemberData" class="hidden fixed flex flex-col justify-center items-center  z-50  w-full h-screen p-4">
  <input type="hidden" id="csrtfTokenid" class="csrtfToken" id="">
      <div class="flex flex-col  max-w-md w-full  bg-slate-100  rounded-lg shadow-lg p-4">
        <div class="flex flex-row space-y-2 ">
          <h2 class=" font-bold flex justify-end w-2/3 pr-4 text-xl">Add member</h2>
          <i id="closenewmemberdata" class="ri-close-large-fill flex justify-end w-1/3 text-green-400 font-bold text-2xl hover:scale-105"></i>
        </div>
        <div>
          <input type="hidden" id="connectionNode" value=""/>
          <input type="hidden" id="connectionUnid" value=""/>
          <input type="hidden" id="connectionContinumRelationship" value=""/>
          <input type="hidden" id="connectionDirection" value=""/>
        </div>
        <div class="flex flex-col gap-2">
          <label for="img">Photo</label>
          <input type="file" id="newmemberPhoto" name="img" accept="image/jpeg, image/png, image/jpg" class="border rounded p-2">
          <img id="newMemberpreview" class="w-32 h-32 object-cover rounded hidden" alt="Preview">
        </div>
        <div class="flex flex-col gap-2">
          <label for="img">First Name</label>
          <input id="newmembersfname" type="text" placeholder="Full name" class="px-2 py-1 rounded-lg outline-none border">
        </div>
        <div class="flex flex-col gap-2">
          <label for="img">Last Name</label>
          <input id="newmemberslname" type="text" placeholder="Full name" class="px-2 py-1 rounded-lg outline-none border">
        </div>
        <div class="flex flex-col gap-2">
          <label for="img">ID</label>
          <input id="newMemberidNumber" type="text" placeholder="Full name" class="px-2 py-1 rounded-lg outline-none border">
        </div>
        <div class="flex flex-col gap-2">
          <label for="img">Born (Sunrise)</label>
          <input id="newMemberbirthDate" type="date"  class="px-2 py-1 rounded-lg outline-none border">
        </div>
          <!-- Deceased toggle -->
      <div class="flex items-center gap-2 mb-2">
        <input type="checkbox" id="isDeceasednewMember" class="w-4 h-4 text-green-500 border-gray-300 rounded">
        <label for="isDeceased" class="text-gray-700">Deceased</label>
      </div>

      <!-- Date of death (hidden by default) -->
      <div id="newMemberdeathContainer" class="flex flex-col gap-2 mb-2 hidden">
        <label for="died">Passed Away (Sunset)</label>
        <input type="date" id="newMemberdied" class="px-2 py-1 rounded-lg outline-none border">
      </div>
      <div class="flex flex-col gap-2">
          <label for="img">nick name</label>
          <input id="newMembernickName" type="text" placeholder="mama or baba ann" class="px-2 py-1 rounded-lg outline-none border">
        </div>
        <div class="flex flex-col gap-2 m-4  text-center  mx-auto">
          <button id="addNewMemberBtn" class="bg-green-400 px-2 py-1 rounded-lg w-max text-white mx-auto">Add</button>
        </div>
    </div>
        
  </div>
  <!-- edit  -->
  <div id="editnodeData" class="hidden fixed flex flex-col justify-center items-center  z-50 h-screen overflow-y-auto  w-full h-screen my-4">
  <input type="hidden" id="editcsrtfTokenid" class="csrtfToken" id="">
      <div class="flex flex-col  max-w-md w-full  bg-slate-100  rounded-lg shadow-lg p-4">
        <div class="flex flex-row space-y-2 ">
          <h2 class=" font-bold flex justify-end w-2/3 pr-4 text-xl">Edit Member Details</h2>
          <i id="closeeditdata" class="ri-close-large-fill flex justify-end w-1/3 text-green-400 font-bold text-2xl hover:scale-105"></i>
        </div>
        <div class="flex flex-col gap-2">
          <label for="img">Photo</label>
          <input type="file" id="editmemberPhoto" name="img" accept="image/jpeg, image/png, image/jpg" class="border rounded p-2">
          <img id="editpreview" class="w-32 h-32 object-cover rounded hidden" alt="Preview">
        </div>
        <div class="flex flex-col gap-2">
          <label for="img">First Name</label>
          <input id="editfname" type="text" placeholder="Full name" class="px-2 py-1 rounded-lg outline-none border">
        </div>
        <div class="flex flex-col gap-2">
          <label for="img">Last Name</label>
          <input id="editlname" type="text" placeholder="Full name" class="px-2 py-1 rounded-lg outline-none border">
        </div>
        <div class="flex flex-col gap-2">
          <label for="img">ID</label>
          <input id="editidNumber" type="text" placeholder="Full name" class="px-2 py-1 rounded-lg outline-none border">
        </div>
        <div class="flex flex-col gap-2">
          <label for="img">Born (Sunrise)</label>
          <input id="editbirthDate" type="date"  class="px-2 py-1 rounded-lg outline-none border">
        </div>
          <!-- Deceased toggle -->
      <div class="flex items-center gap-2 mb-2">
        <input type="checkbox" id="isDeceasededit" class="w-4 h-4 text-green-500 border-gray-300 rounded">
        <label for="isDeceasededit" class="text-gray-700">Deceased</label>
      </div>

      <!-- Date of death (hidden by default) -->
      <div id="deathContaineredit" class="flex flex-col gap-2 mb-2 hidden">
        <label for="died">Passed Away (Sunset)</label>
        <input type="date" id="editdied" class="px-2 py-1 rounded-lg outline-none border">
      </div>
      <div class="flex flex-col gap-2">
          <label for="img">nick name</label>
          <input id="editnickName" type="text" placeholder="mama or baba ann" class="px-2 py-1 rounded-lg outline-none border">
        </div>
         <div class="flex flex-col gap-2">
          <label for="img">Role</label>
          <input id="editrole" type="text" placeholder="Father, Mother or Child" class="px-2 py-1 rounded-lg outline-none border">
        </div>
        <div class="flex flex-col gap-2 m-4  text-center  mx-auto">
          <button id="saveChangesBtn" class="bg-green-400 px-2 py-1 rounded-lg w-max text-white mx-auto">Save Changes</button>
        </div>
    </div>
        
  </div>
  <!-- popup alert -->
    <div id="alertnodeData" class="hidden fixed flex flex-col justify-center items-center  z-50 h-screen overflow-y-auto  w-full h-screen my-4 mx-4">
  <input type="hidden" id="editcsrtfTokenid" class="csrtfToken" id="">
      <div class="flex flex-col  max-w-md w-full  bg-slate-100  rounded-lg shadow-lg p-4">
        <div class="flex flex-row space-y-2 ">
          <h2 class=" font-bold flex justify-end w-2/3 pr-4 text-xl">Info Notification</h2>
          <i id="closealertdata" class="ri-close-large-fill flex justify-end w-1/3 text-green-400 font-bold text-2xl hover:scale-105"></i>
        </div>
        <div class="flex flex-col gap-2">
         <p>
          Kindly update your profile details so that your family tree can be saved by clicking <strong>Profile</strong>.  
          If this is for trial purposes only, just click <strong>Continue</strong>.
        </p>
        </div>
       
        <div class="flex flex-row gap-2 m-4  text-center  mx-auto">
          <button id="profileBtn" class="bg-green-400 px-2 py-1 rounded-lg w-max text-white mx-auto">Profile</button>
          <button id="continueWithouttsBtn" class="bg-green-400 px-2 py-1 rounded-lg w-max text-white mx-auto">Continue</button>
        </div>
    </div>
        
  </div>
  <!-- delete container -->
  <div id="removenodeData" class="hidden fixed flex flex-col justify-center items-center  z-50 h-screen overflow-y-auto  w-full h-screen my-4 mx-4">
    <input type="hidden" id="removecsrtfTokenid" class="csrtfToken" >
    <input type="hidden" id="removeMemberId" ">
    <div class="flex flex-col  max-w-md w-full  bg-slate-100  rounded-lg shadow-lg p-4">
      <div class="flex flex-row space-y-2 ">
        <h2 class=" font-bold flex justify-end w-2/3 pr-4 text-xl">Remove a member</h2>
        
      </div>
      <div class="flex flex-col gap-2">
        <p>
        Kindly not this will delete all member info to continue click <strong>continue</strong>.  
        If you do not wish to continue, just click <strong>cancel</strong>.
      </p>
      </div>
      
      <div class="flex flex-row gap-2 m-4  text-center  mx-auto">
        <button id="cancelBtn" class="bg-green-400 px-2 py-1 rounded-lg w-max text-white mx-auto">Cancel</button>
        <button id="removeMemberBtn" class="bg-green-400 px-2 py-1 rounded-lg w-max text-white mx-auto">Continue</button>
      </div>
    </div>
        
  </div>
 <script type="module" src="../js/csrf.js"></script>
 <script type="module" src="../js/chart.js"></script>
 <script type="module" src="../js/main.js"></script>
 <script type="module"  src="../js/addMember.js"></script>
 <script type="module"  src="../js/logout.js"></script>
</body>
</html>