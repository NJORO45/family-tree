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
      const resetPassword = document.querySelector("#resetPassword");
    const csrtfTokenid = document.querySelector("#csrtfTokenid");
    const Email = document.querySelector("#email");
    const Tel = document.querySelector("#tel");
    let emailstate= false;
    let telstate= false;
        // loginTel.addEventListener("blur",()=>{
        //     if(!validateAndFormatKenyanPhone(loginTel.value)){
        //        console.log("not") 
        //        telstate=false;
        //     }else{
        //         console.log("is")
        //         telstate=true;
        //     }
        //     return;
        // });
        Email.addEventListener("blur",()=>{
            if(!isValidEmail(Email.value)){
               console.log("not") 
               emailstate=false;
            }else{
                console.log("is")
                emailstate=true;
            }
            return;
        });
        resetPassword.addEventListener("click",async()=>{
            const csrtfTokenValue = csrtfTokenid.value;
            // Validate only the filled field
            if (Email.value !== "" && !emailstate) {
            showAlert({
                alertMessage,
                message: "Invalid email format.",
                resetPassword,
            });
            return;
            }
    
            // if (Tel.value !== "" && !telstate) {
            // showAlert({
            //     alertMessage,
            //     message: "Invalid phone number format.",
            //     resetPassword,
            // });
            // return;
            // }
            if (Tel.value !== "" ) {
                telstate=false;
            showAlert({
                alertMessage,
                message: "OTP for phone nmber will be updated soon",
                resetPassword,
            });
            return;
            }
            if(Email.value!="" &&Tel.value!=""){
                showAlert({
                    alertMessage,
                    message: "Choose email or tel not both",
                    resetPassword,
                });
                return;
            }
            if(Email.value==="" &&Tel.value===""){
                showAlert({
                    alertMessage,
                    message: "Please enter either your email or phone number.",
                    resetPassword,
                });
                return;
            }
            const postData = new FormData();
            postData.append("passwordResetotpStatus", true);
            postData.append("email", sanitize(Email.value));
            postData.append("tel", sanitize(Tel.value));
            postData.append("csrtfToken", sanitize(csrtfTokenValue));
    
            
            try {
                const response = await fetch("insertData.php", {
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
                    addNewNodeBtn: resetPassword,
                    url:"otpVerification.php?token=" + result.otpvalidationToken
                });
                return;
                } else {
                showAlert({
                    alertMessage,
                    message: result.message || "Invalid login credentials.",
                    addNewNodeBtn: resetPassword,
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
                addNewNodeBtn: resetPassword,
                });
            }
        });
});