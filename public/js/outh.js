import { showTimedAlert } from './utilities/alerthandler.js';
import { showAlert } from './utilities/alerthandler.js';

addEventListener("DOMContentLoaded",()=>{
    const login = document.querySelector("#login");
    const continuebtn = document.querySelector("#continue");
    const csrtfTokenid = document.querySelector("#csrtfTokenid");
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