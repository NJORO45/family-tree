import { showTimedAlert } from './utilities/alerthandler.js';
import { showAlert } from './utilities/alerthandler.js';
import { scrolltotop } from './utilities/alerthandler.js';
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
    const changePasswordBtn = document.querySelector("#changePasswordBtn");
    const ConfirmNewPassword = document.querySelector("#ConfirmNewPassword");
    const newPassword = document.querySelector("#newPassword");
    const oldPassword = document.querySelector("#oldPassword");
    const confirmpassError = document.querySelector("#confirmpassError");
    const newpassError = document.querySelector("#newpassError");
    const oldpassError = document.querySelector("#oldpassError");
    let oldPasswordStatus=false;
    let newPasswordStatus=false;
    let ConfirmNewPasswordStatus=false;
    let selectedValue = null;
    const alertMessage = document.querySelector("#alertMessage");
    const csrtftokenpaswordReset = document.querySelector("#csrtftokenpaswordReset");
    const csrtftokenpreference = document.querySelector("#csrtftokenpreference");
    const saveOptionBtn = document.querySelector("#saveOptionBtn");
    const SelectedOption = document.getElementsByName("notification");
    const deleteAccountBtn = document.querySelector("#deleteAccountBtn");
    const deactivateAccount = document.querySelector("#deactivateAccount");
    const deactivateTrue = document.querySelector("#deactivateTrue");
    const deactivatecsrtToken = document.querySelector("#deactivatecsrtToken");
    const p = document.querySelector("p");
    scrolltotop();
    deleteAccountBtn.addEventListener("click",()=>{
        deactivateAccount.classList.remove("hidden");
        deactivateAccount.classList.add("flex");

    });
    deactivateTrue.addEventListener("click",()=>{
        const token = deactivatecsrtToken.value;
            async function deactivateAccountFunction() {
            try{
                const postData = {
                    deactivateAccountStatus:true,
                    csrtfToken:sanitize(token)
                    
                };
                const response = await fetch('insertData.php',{
                    method:"POST",
                    headers:{"Content-Type":"application/json"},
                    body:JSON.stringify(postData)
                });
                const text = await response.text();
                try{
                    const results = JSON.parse(text);
                    if(results.success){
                        // success
                        p.textContent = results.message;
                        alertMessage.classList.remove("hidden");
                        alertMessage.classList.add("flex","animate-slide-down");
                        setTimeout(() => {
                            alertMessage.classList.remove("animate-slide-down");
                            alertMessage.classList.add("animate-slide-up");
                        }, 2000);
                        //close modal and clear content
                        setTimeout(()=>{
                            alertMessage.classList.add("hidden");
                            alertMessage.classList.remove("flex");
                            alertMessage.classList.remove("animate-slide-down","animate-slide-up");
                            loginPopup.classList.add("hidden");
                            loginPopup.classList.remove("flex");
                        },2400);
                        
                    }else{
                        p.textContent = results.message;
                        alertMessage.classList.remove("hidden");
                        alertMessage.classList.add("flex");
                        alertMessage.classList.add("animate-slide-down");
                        setTimeout(() => {
                            alertMessage.classList.remove("animate-slide-down");
                            alertMessage.classList.add("animate-slide-up");
                        }, 2000);
                        setTimeout(() => {
                            alertMessage.classList.add("hidden","animate-slide-down");
                            alertMessage.classList.remove("flex","animate-slide-up");
                        }, 2400);
                    }
                }
                catch(jsonErr){
                    console.log("server error: " + jsonErr);
                }
            }catch(error){
                console.log("error runing reset function: " + error);
            }
        }
        deactivateAccountFunction();
    });
    saveOptionBtn.addEventListener("click",()=>{
        SelectedOption.forEach(radio=>{
            if(radio.checked){
                selectedValue = radio.value;
               // console.log(selectedValue);
                const token = csrtftokenpreference.value;
                    async function preferenceFunction() {
                    try{
                        const postData = {
                            preferenceStatus:true,
                            selectedValue:sanitize(selectedValue),
                            csrtfToken:sanitize(token)
                            
                        };
                        const response = await fetch('insertData.php',{
                            method:"POST",
                            headers:{"Content-Type":"application/json"},
                            body:JSON.stringify(postData)
                        });
                        const text = await response.text();
                        try{
                            const results = JSON.parse(text);
                            if(results.success){
                                //login success
                                p.textContent = results.message;
                                alertMessage.classList.remove("hidden");
                                alertMessage.classList.add("flex","animate-slide-down");
                                setTimeout(() => {
                                    alertMessage.classList.remove("animate-slide-down");
                                    alertMessage.classList.add("animate-slide-up");
                                }, 2000);
                                //close modal and clear content
                                setTimeout(()=>{
                                    alertMessage.classList.add("hidden");
                                    alertMessage.classList.remove("flex");
                                    alertMessage.classList.remove("animate-slide-down","animate-slide-up");
                                    loginPopup.classList.add("hidden");
                                    loginPopup.classList.remove("flex");
                                },2400);
                            }else{
                                p.textContent = results.message;
                                alertMessage.classList.remove("hidden");
                                alertMessage.classList.add("flex");
                                alertMessage.classList.add("animate-slide-down");
                                setTimeout(() => {
                                    alertMessage.classList.remove("animate-slide-down");
                                    alertMessage.classList.add("animate-slide-up");
                                }, 2000);
                                setTimeout(() => {
                                    alertMessage.classList.add("hidden","animate-slide-down");
                                    alertMessage.classList.remove("flex","animate-slide-up");
                                }, 2400);
                            }
                        }
                        catch(jsonErr){
                            console.log("server error: " + jsonErr);
                        }
                    }catch(error){
                        console.log("error runing reset function: " + error);
                    }
                }
                preferenceFunction();
            }else{
                p.textContent = "Select an option to continue";
                alertMessage.classList.remove("hidden");
                alertMessage.classList.add("flex");
                loginBtn.disabled=false;
                alertMessage.classList.add("animate-slide-down");
                setTimeout(() => {
                    alertMessage.classList.remove("animate-slide-down");
                    alertMessage.classList.add("animate-slide-up");
                }, 2000);
                setTimeout(() => {
                    alertMessage.classList.add("hidden","animate-slide-down");
                    alertMessage.classList.remove("flex","animate-slide-up");
                }, 2400);
            }
        });
    });
    oldPassword.addEventListener("blur",()=>{
        if(oldPassword.value===""){
            oldpassError.textContent="Old password is required";
            oldPasswordStatus=false;
        }else{
            oldpassError.textContent="";
            oldPasswordStatus=true;
        }
    });
     newPassword.addEventListener("blur",()=>{
        if(newPassword.value===""){
            newpassError.textContent="New password is required";
            newPasswordStatus=false;
        }else{
            newpassError.textContent="";
            newPasswordStatus=true;
        }
    });
     ConfirmNewPassword.addEventListener("input",()=>{
        if(newPassword.value==ConfirmNewPassword.value){
            confirmpassError.textContent="";
            ConfirmNewPasswordStatus=true;
        }else{
            confirmpassError.textContent="Password doesn't match";
            ConfirmNewPasswordStatus=false;
        }
    });
    changePasswordBtn.addEventListener("click",()=>{
        const token = csrtftokenpaswordReset.value;
        if(oldPasswordStatus && newPasswordStatus && ConfirmNewPasswordStatus){
            async function passwordResetFunction() {
                try{
                     const postData = new FormData();
                    postData.append("passwordResetStatus", "true");
                    postData.append("oldpassword", sanitize(oldPassword.value));
                    postData.append("confirmnewpassword", sanitize(ConfirmNewPassword.value));
                    postData.append("csrtfToken", sanitize(token));
                    const response = await fetch('insertData.php',{
                        method:"POST",
                        // headers:{"Content-Type":"application/json"},
                        body:postData
                    });
                    const text = await response.text();
                    console.log(text)
                    try{
                        const results = JSON.parse(text);
                        if(results.success){
                            showTimedAlert({
                            alertMessage,
                            message: results.message,
                            changePasswordBtn,
                            
                            url:""
                            });
                        }else{
                            p.textContent = results.message;
                            showAlert({
                                alertMessage,
                                message: results.message,
                                changePasswordBtn,
                            });
                        }
                    }
                    catch(jsonErr){
                        console.log("server error: " + jsonErr);
                    }
                }catch(error){
                    console.log("error runing reset function: " + error);
                }
            }
            passwordResetFunction();
        }else{
            showAlert({
                    alertMessage,
                    message: "Check your inputs" ,
                    changePasswordBtn,
                });
        }
    })
});