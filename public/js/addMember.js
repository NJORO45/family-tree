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
        // Convert to +254 format
        return "+254" + cleaned.substring(1);
    } else {
        return null; // invalid number
    }
}
async function getuserStatus() {
           
            const response = await fetch('getuserstatus.php',{
                method:"GET",
                headers:{"Accept":"application/json"}  
            });
            const text = await response.text();
            console.log(text)
            try{
                const result = JSON.parse(text);
                console.log(result)
                return result.message;
            }
            catch(jsonErr){
              console.error("JSON parse error:", jsonErr, text);
              return [];
            }
}


addEventListener("DOMContentLoaded",async()=>{
   //const data = await getuserStatus();
    const userName = document.querySelector("#userName");
    const alertMessage = document.querySelector("#alertMessage");
    const p = document.querySelector("p");
    const closeNodedata = document.querySelector("#closeNodedata");
    const addnewNode = document.querySelector("#addnewNode");
    const newNodeData = document.querySelector("#newNodeData");
    const role = document.querySelector("#role");
    const addNewNodeBtn = document.querySelector("#addNewNodeBtn");
    const memberPhoto = document.querySelector("#memberPhoto");
    const preview = document.querySelector("#preview");

    const isDeceased = document.querySelector("#isDeceased");
    const deathContainer = document.querySelector("#deathContainer");

    const fname = document.querySelector("#fname");
    const lname = document.querySelector("#lname");
    const idNumber = document.querySelector("#idNumber");
    const tel = document.querySelector("#tel");
    const email = document.querySelector("#email");
    const birthDate = document.querySelector("#birthDate");
    const died = document.querySelector("#died");
    const nickName = document.querySelector("#nickName");
    const csrtfTokenid = document.querySelector("#csrtfTokenid");
    const alertnodeData = document.querySelector("#alertnodeData");
    const closealertdata = document.querySelector("#closealertdata");
    const continueWithouttsBtn = document.querySelector("#continueWithouttsBtn");
    //const rankState = document.querySelector("#rankState");
    let emailstate = false; 
    let telstate =false;
    addnewNode.addEventListener("click",()=>{
        newNodeData.classList.toggle("hidden");
    });
    closeNodedata.addEventListener("click",()=>{
        newNodeData.classList.add("hidden");
    });
closealertdata.addEventListener("click",()=>{
        alertnodeData.classList.add("hidden");
    });

  memberPhoto.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
      preview.src = URL.createObjectURL(file);
      preview.classList.remove("hidden");
    }else{
        preview.src="";
        preview.classList.add("hidden");
    }
  });
     tel.addEventListener("blur",()=>{
            if(!validateAndFormatKenyanPhone(tel.value)){
               telstate=false;
               showAlert({
                  alertMessage,
                  message: "Invalid tel fomart" ,
                  addNewNodeBtn,
                });
            }else{
                telstate=true;
            }
            return;
        });
        email.addEventListener("blur",()=>{
            if(!isValidEmail(email.value)){
               console.log("not") 
               emailstate=false;
               showAlert({
                  alertMessage,
                  message: "Invalid email fomart" ,
                  addNewNodeBtn,
                });
            }else{
                console.log("is")
                emailstate=true;
            }
            return;
        });
  addNewNodeBtn.addEventListener("click",async()=>{
    //check if user is guest and if they dont want to save the data
     const rankState = document.querySelector("#rankState");
    console.log(rankState.value)
    if(rankState.value=="false"){
      showAlert({
        alertMessage,
        message: "Only admin can add family tree" ,
        addNewNodeBtn,
      });
      return;
    }
    if(email.value==""){
          emailstate=true;
          
      }
       if(tel.value==""){
          telstate=true;
          
      }
    const data = await getuserStatus();
    console.log(data)
    if(data[0].temp_user=='true' && (data[0].guest_continuem!="" || data[0].guest_continuem== "undefined")){
      //popup alert to alert the user if they want the data to be saved or not
      alertnodeData.classList.remove("hidden");
      console.log(data[0].temp_user)
    }else{
    if(nickName.value==="" && fname.value==="" ){
        showAlert({
        alertMessage,
        message: "Please enter at least a nickname or first name." ,
        addNewNodeBtn,
      });
      return;
    }
    if(emailstate===false || telstate===false ){
       showAlert({
        alertMessage,
        message: "Please enter correct fomart email/tel" ,
        addNewNodeBtn,
      });
      return;
    }
        async function addnewNodeFunction() {
                const csrtfTokenValue = csrtfTokenid.value;
                const fname = document.querySelector("#fname");
                const lname = document.querySelector("#lname");
                const idNumber = document.querySelector("#idNumber");
                const tel = document.querySelector("#tel");
                const email = document.querySelector("#email");
                const birthDate = document.querySelector("#birthDate");
                const died = document.querySelector("#died");
                const nickName = document.querySelector("#nickName");
                const role = document.querySelector("#role");
                const postData ={
                    addNodeStatus:true,
                    fname:sanitize(fname.value),
                    lname:sanitize(lname.value),
                    idNumber:sanitize(idNumber.value),
                    tel:sanitize(tel.value),
                    email:sanitize(email.value),
                    died:sanitize(died.value),
                    birthDate:sanitize(birthDate.value),
                    nickName:sanitize(nickName.value),
                    role:sanitize(role.value),
                    csrtfToken:sanitize(csrtfTokenValue)
                };
                 // Now create FormData and add all fields
                const formData = new FormData();

                for (const key in postData) {
                formData.append(key, postData[key]);
                }

                // Add file if available
                if (memberPhoto.files[0]) {
                formData.append("photo", memberPhoto.files[0]);
                }
                console.log(formData)
             const response = await fetch('insertData.php',{
                method:"POST",
                // headers:{"Content-Type":"multipart/form-data"},
                body:formData
             });
             const text = await response.text();
             console.log(text);
             try{
                const result = JSON.parse(text);
               // console.log(result);
                if(result.success){
                    //login success
                    showTimedAlert({
                      alertMessage,
                      message: result.message,
                      addNewNodeBtn,
                      newNodeData,
                      url:""
                    });
                }else{
                    showAlert({
                        alertMessage,
                        message: result.message ,
                        addNewMemberBtn,
                    });
                }
             }
             catch(jsonErr){
                console.log("response error:" + jsonErr);
             }
          }
          addnewNodeFunction();
    
  }
  });
continueWithouttsBtn.addEventListener("click",async ()=>{
  //continue donnt save tree to user
              // Now create FormData and add all fields
  const csrtfTokenValue = csrtfTokenid.value;
  const formData = new FormData();
  formData.append("guest_continuemStatus", true);
  formData.append("csrtfToken", sanitize(csrtfTokenValue));

  console.log(formData)
    const response = await fetch('insertData.php',{
      method:"POST",
      // headers:{"Content-Type":"multipart/form-data"},
      body:formData
    });
    const text = await response.text();
    console.log(text);
    try{
      const result = JSON.parse(text);
      // console.log(result);
      if(result.success){
          //login success
          showTimedAlert({
            alertMessage,
            message: result.message,
            addNewNodeBtn,
            newNodeData,
            url:""
          });
      }else{
          showAlert({
              alertMessage,
              message: result.message ,
              addNewMemberBtn,
          });
      }
    }
    catch(jsonErr){
      console.log("response error:" + jsonErr);
    }
});


  isDeceased.addEventListener('change', () => {
    if (isDeceased.checked) {
      deathContainer.classList.remove('hidden');
    } else {
      deathContainer.classList.add('hidden');
      document.getElementById('died').value = ''; // clear value if unchecked
    }
  });
});