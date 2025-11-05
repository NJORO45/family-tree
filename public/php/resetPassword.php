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
    <div class="w-full h-screen flex overflow-hidden justify-center items-start pt-20 bg-gray-50">
        <div class="flex flex-col space-y-6 bg-white shadow-lg rounded-lg p-8 max-w-sm w-full">
           
        <div class="flex flex-col">
                <label for="i_number" class="font-medium text-gray-700">Tel</label>
                <input id="tel" type="number" class="border-2 border-green-300 outline-none rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400">
            </div>
            <div class="flex flex-row items-center">
                <div class="flex-grow border-t border-gray-300"></div>
                <span class="px-3 text-gray-500">or</span>
                <div class="flex-grow border-t border-gray-300"></div>
            </div>
            <div class="flex flex-col">
                <label for="email" class="font-medium text-gray-700">Email</label>
                <input id="email" type="email" class="border-2 border-green-300 outline-none rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400">
            </div>
             <input type="hidden" id="csrtfTokenid" class="csrtfToken">
            <div class="flex flex-col gap-2">
                <button id="resetPassword" class="bg-green-500 px-4 py-2 rounded-lg shadow-lg text-white text-xl font-semibold hover:bg-green-400 transition">Reset Password</button>
                
            </div>
        </div>
    </div>
</body>
 <script type="module"  src="../js/resetPassword.js"></script>
 <script type="module"  src="../js/csrf.js"></script>
</html>