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

    const name = document.querySelector("#name");
    const idNumber = document.querySelector("#idNumber");
    const birthDate = document.querySelector("#birthDate");
    const died = document.querySelector("#died");
    const nickName = document.querySelector("#nickName");
    const csrtfTokenid = document.querySelector("#csrtfTokenid");

    addnewNode.addEventListener("click",()=>{
        newNodeData.classList.toggle("hidden");
    });
    closeNodedata.addEventListener("click",()=>{
        newNodeData.classList.add("hidden");
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
  addNewNodeBtn.addEventListener("click",e=>{
    if(nickName.value=="" && name.value==""){
        showAlert({
        alertMessage,
        message: "Please enter at least a nickname or name." ,
        addNewMemberBtn,
      });
    }else{
        async function addnewNodeFunction() {
                const csrtfTokenValue = csrtfTokenid.value;
                const name = document.querySelector("#name");
                const idNumber = document.querySelector("#idNumber");
                const birthDate = document.querySelector("#birthDate");
                const died = document.querySelector("#died");
                const nickName = document.querySelector("#nickName");
                const role = document.querySelector("#role");
                const postData ={
                    addNodeStatus:true,
                    name:sanitize(name.value),
                    idNumber:sanitize(idNumber.value),
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



  isDeceased.addEventListener('change', () => {
    if (isDeceased.checked) {
      deathContainer.classList.remove('hidden');
    } else {
      deathContainer.classList.add('hidden');
      document.getElementById('died').value = ''; // clear value if unchecked
    }
  });
});