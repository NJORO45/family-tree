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
addEventListener("DOMContentLoaded",()=>{
    const verifyOtpBtn = document.querySelector("#verifyOtpBtn");
    const csrtfTokenid = document.querySelector("#csrtfTokenid");
    const otpInput = document.querySelector("#otpInput");
    const expiryStateid = document.querySelector("#expiryStateid");
    const newPasswordFoem = document.querySelector("#newPasswordFoem");
    setTimeout(()=>{
        if(expiryStateid.value=='true'){
            console.log("true")
            showTimedAlert({
                alertMessage,
                message: 'Invalid or expired token. Please request a new OTP.',
                addNewNodeBtn: null,
                url:'resetPassword.php'
                });
        }
    },1000);
    verifyOtpBtn.addEventListener("click",async()=>{
        const csrtfTokenValue = csrtfTokenid.value;
        //check if empty 
        if(otpInput.value== ""){
             showAlert({
                alertMessage,
                message: "Empty OTP",
                verifyOtpBtn,
            });
            return;
        }else{
            const postData = new FormData();
            postData.append("validateotpStatus", true);
            postData.append("otpInput", sanitize(otpInput.value));
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
                showAlert({
                    alertMessage,
                    message: result.message,
                    addNewNodeBtn: verifyOtpBtn
                });
                newPasswordFoem.classList.remove("hidden");
                return;
                } else {
                showAlert({
                    alertMessage,
                    message: result.message,
                    addNewNodeBtn: verifyOtpBtn,
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
                addNewNodeBtn: verifyOtpBtn,
                });
            }
        }
    });
});