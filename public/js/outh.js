import { showTimedAlert } from './utilities/alerthandler.js';
import { showAlert } from './utilities/alerthandler.js';
function sanitize(input) {
    if (typeof input !== "string") return input;

    const map = {
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;",
        "`": "&#96;"
    };

    return input
        .trim()
        .replace(/[&<>"'`]/g, match => map[match])
        .replace(/\r?\n|\r/g, " "); // normalize newlines
}
function isValidEmail(value) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim());
}
function validateAndFormatKenyanPhone(phone) {
    // Remove spaces, hyphens, parentheses
    const cleaned = phone.replace(/[\s-()]/g, "");

    // Check if it starts with 07 and has exactly 10 digits
    const regex = /^07\d{8}$/;

    if (regex.test(cleaned)) {
        //leaned.substring(1); will strip the leading 0
        return  cleaned;
    } else {
        return null; // invalid number
    }
}
addEventListener("DOMContentLoaded",()=>{
    const login = document.querySelector("#login");
    const continuebtn = document.querySelector("#continue");
    const csrtfTokenid = document.querySelector("#csrtfTokenid");
    const loginEmail = document.querySelector("#loginEmail");
    const loginPassword = document.querySelector("#loginPassword");
    const loginTel = document.querySelector("#loginTel");
    let emailstate= false;
    let telstate= false;
    //login
    loginTel.addEventListener("blur",()=>{
            if(!validateAndFormatKenyanPhone(loginTel.value)){
               console.log("not") 
               telstate=false;
            }else{
                console.log("is")
                telstate=true;
            }
            return;
        });
        loginEmail.addEventListener("blur",()=>{
            if(!isValidEmail(loginEmail.value)){
               console.log("not") 
               emailstate=false;
            }else{
                console.log("is")
                emailstate=true;
            }
            return;
        });
    login.addEventListener("click",async()=>{
        const csrtfTokenValue = csrtfTokenid.value;
        // Validate only the filled field
        if (loginEmail.value !== "" && !emailstate) {
        showAlert({
            alertMessage,
            message: "Invalid email format.",
            login,
        });
        return;
        }

        if (loginTel.value !== "" && !telstate) {
        showAlert({
            alertMessage,
            message: "Invalid phone number format.",
            login,
        });
        return;
        }
        if(loginEmail.value!="" &&loginTel.value!=""){
            showAlert({
                alertMessage,
                message: "Choose email or tel not both",
                login,
            });
            return;
        }
        if(loginEmail.value==="" &&loginTel.value===""){
            showAlert({
                alertMessage,
                message: "Please enter either your email or phone number.",
                login,
            });
            return;
        }
        if(loginPassword.value===""){
            showAlert({
                alertMessage,
                message: "Password is required.",
                login,
            });
            return;
        }
        const postData = new FormData();
        postData.append("loginStatus", true);
        postData.append("loginemail", sanitize(loginEmail.value));
        postData.append("logintel", sanitize(loginTel.value));
        postData.append("loginpassword", sanitize(loginPassword.value));
        postData.append("csrtfToken", sanitize(csrtfTokenValue));

        
        try {
            const response = await fetch("php/insertData.php", {
            method: "POST",
            body: postData,
            });
            const text = await response.text();
            console.log(text)
            try{
                const result = JSON.parse(text);

            if (result.success) {
            showTimedAlert({
                alertMessage,
                message: result.message,
                addNewNodeBtn: login,
                url:"php/dashboard.php"
            });
            return;
            } else {
            showAlert({
                alertMessage,
                message: result.message || "Invalid login credentials.",
                addNewNodeBtn: login,
            });
            return;
            }
            } catch(jsonErr){
                console.error("error executing function:", jsonErr);
            }
        } catch (error) {
            console.error("Login error:", error);
            showAlert({
            alertMessage,
            message: "Server error. Please try again.",
            addNewNodeBtn: login,
            });
        }
    });
    ///login without creentials  update details while using the system
    
    continuebtn.addEventListener("click",async ()=>{
        const postData = new FormData();
        postData.append("continueWithoutLoginStatus", "true");
        postData.append("csrtfToken", csrtfTokenid.value);
        try{
            const response = await fetch('php/insertData.php',{
                method:"POST",
                body:postData
            });
            const text = await response.text();
            console.log(text)
            try{
                const result = JSON.parse(text);
                if(result.success){
                     showTimedAlert({
                      alertMessage,
                      message: result.message,
                      continuebtn,
                      url:"php/dashboard.php"
                    });
                }else{
                    showAlert({
                        alertMessage,
                        message: "error creating session" ,
                        continuebtn,
                    });
                }
            }catch(jsonErr){
                console.log("server error :". jsonErr);
            }
    }
    catch(error){
        console.log("error runing function".error);
    }
    });
});