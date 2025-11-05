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
    const newPassword = document.querySelector("#newPassword");
    const ConfirmPassword = document.querySelector("#ConfirmPassword");
    const updatePasswordBtn = document.querySelector("#updatePasswordBtn");
    const csrtfTokenid = document.querySelector("#csrtfTokenid");
    const eyeIcon = document.querySelector("#eyeIcon");
    const eyeIcon1 = document.querySelector("#eyeIcon1");
    const confirmPasswordError = document.querySelector("#confirmPasswordError");
    let passwordState=false;
    eyeIcon.addEventListener("click",()=>{
        if (ConfirmPassword.type === "password") {
            ConfirmPassword.type = "text"; // 👁️ show password
            eyeIcon.classList.add("ri-eye-off-line");
            eyeIcon.classList.remove("ri-eye-fill");
        } else {
            ConfirmPassword.type = "password"; // 🙈 hide password
            eyeIcon.classList.add("ri-eye-fill");
            eyeIcon.classList.remove("ri-eye-off-line");
        }
    });
    eyeIcon1.addEventListener("click",()=>{
        if (newPassword.type === "password") {
            newPassword.type = "text"; // 👁️ show password
            eyeIcon1.classList.add("ri-eye-off-line");
            eyeIcon1.classList.remove("ri-eye-fill");
        } else {
            newPassword.type = "password"; // 🙈 hide password
            eyeIcon1.classList.add("ri-eye-fill");
            eyeIcon1.classList.remove("ri-eye-off-line");
        }
    });
    //confirm input password 
    ConfirmPassword.addEventListener("input",()=>{
        if(newPassword.value!==ConfirmPassword.value){
            confirmPasswordError.textContent="No Match";
            passwordState=false;
        }else{
            confirmPasswordError.textContent=" ";
            passwordState=true
        }
    });
    updatePasswordBtn.addEventListener("click",async()=>{
        const csrtfTokenValue = csrtfTokenid.value;
        if(passwordState){
            const postData = new FormData();
            postData.append("updatePasswordStatus", true);
            postData.append("newPassword", sanitize(newPassword.value));
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
                    addNewNodeBtn: updatePasswordBtn,
                    url:"../index.html"
                });
                return;
                } else {
                showAlert({
                    alertMessage,
                    message: result.message,
                    addNewNodeBtn: updatePasswordBtn,
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
                addNewNodeBtn: updatePasswordBtn,
                });
            }
        }else{
            showAlert({
                alertMessage,
                message: "Check Your Password",
                addNewNodeBtn: updatePasswordBtn,
                });
        }
    });
});