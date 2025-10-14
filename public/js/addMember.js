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
        p.textContent ="Please enter at least a nickname or name.";
        alertMessage.classList.remove("hidden");
        alertMessage.classList.add("flex");
        addNewNodeBtn.disabled=false;
        alertMessage.classList.add("animate-slide-down");
        setTimeout(() => {
            alertMessage.classList.remove("animate-slide-down");
            alertMessage.classList.add("animate-slide-up");
        }, 2000);
        setTimeout(() => {
            alertMessage.classList.add("hidden","animate-slide-down");
            alertMessage.classList.remove("flex","animate-slide-up");
        }, 2400);
    }else{
        async function addnewNodeFunction() {
                const csrtfTokenValue = csrtfTokenid.value;
                const name = document.querySelector("#name");
                const idNumber = document.querySelector("#idNumber");
                const birthDate = document.querySelector("#birthDate");
                const died = document.querySelector("#died");
                const nickName = document.querySelector("#nickName");
                const postData ={
                    addNodeStatus:true,
                    name:sanitize(name.value),
                    idNumber:sanitize(idNumber.value),
                    died:sanitize(died.value),
                    birthDate:sanitize(birthDate.value),
                    nickName:sanitize(nickName.value),
                    csrtfToken:sanitize(csrtfTokenValue)
                };
                console.log(postData)
             const response = await fetch('insertData.php',{
                method:"POST",
                headers:{"Content-Type":"application/json"},
                body:JSON.stringify(postData)
             });
             const text = await response.text();
             console.log(text);
             try{
                const result = JSON.parse(text);
               // console.log(result);
                if(result.success){
                    //login success
                    addNewNodeBtn.disabled="true";
                    p.textContent = result.message;
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
                        name.value="";
                        idNumber.value="";
                        addNewNodeBtn.disabled=false;
                        alertMessage.classList.remove("animate-slide-down","animate-slide-up");

                    },2400);
                    setTimeout(()=>{
                       // window.location.href="admin/admin.php";
                       newNodeData.classList.add("hidden");
                    },2500);
                }else{
                     p.textContent = result.message;
                    alertMessage.classList.remove("hidden");
                    alertMessage.classList.add("flex");
                    addNewNodeBtn.disabled=false;
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