
import { showTimedAlert } from './utilities/alerthandler.js';
import { showAlert } from './utilities/alerthandler.js';
const nodeCards = {}; // 🔹 global dictionary to store node.id() → card element
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
async function getdataFunction() {
           
            const response = await fetch('getdata.php',{
                method:"GET",
                headers:{"Accept":"application/json"}  
            });
            const text = await response.text();
            console.log(text)
            try{
                const result = JSON.parse(text);
                console.log(result)
             if (Array.isArray(result)) {
                console.log("✅ Data fetched successfully:", result);
                return result;
              } else if (result.success && Array.isArray(result.message)) {
                // Support for wrapped responses too
                return result.message;
              } else {
                console.error("❌ Unexpected format:", result);
                return [];
              }
            }
            catch(jsonErr){
              console.error("JSON parse error:", jsonErr, text);
              return [];
            }
}
function supportsWebP() {
  try {
    return document.createElement('canvas')
      .toDataURL('image/webp')
      .indexOf('data:image/webp') === 0;
  } catch (err) {
    return false;
  }
}

addEventListener("DOMContentLoaded",async()=>{

  const newmemberData = document.querySelector("#newmemberData");
    const alertMessage = document.querySelector("#alertMessage");
    const closenewmemberdata = document.querySelector("#closenewmemberdata");
    const addnewNode = document.querySelector("#addnewNode");
    const newNodeData = document.querySelector("#newNodeData");
    const addNewNodeBtn = document.querySelector("#addNewNodeBtn");
    const newmemberPhoto = document.querySelector("#newmemberPhoto");
    const newMemberpreview = document.querySelector("#newMemberpreview");

    const isDeceasednewMember = document.querySelector("#isDeceasednewMember");
    const newMemberdeathContainer = document.querySelector("#newMemberdeathContainer");

    const newmembersname = document.querySelector("#newmembersname");
    const newMemberidNumber = document.querySelector("#newMemberidNumber");
    const newMemberbirthDate = document.querySelector("#newMemberbirthDate");
    const newMemberdied = document.querySelector("#newMemberdied");
    const newMembernickName = document.querySelector("#newMembernickName");
    const csrtfTokenid = document.querySelector("#csrtfTokenid");
    //edit elements
    const editcsrtfTokenid = document.querySelector("#editcsrtfTokenid");
    const editmemberPhoto = document.querySelector("#editmemberPhoto");
    const editpreview = document.querySelector("#editpreview");
    const editname = document.querySelector("#editname");
    const editidNumber = document.querySelector("#editidNumber");
    const editbirthDate = document.querySelector("#editbirthDate");
    const editdied = document.querySelector("#editdied");
    const editnickName = document.querySelector("#editnickName");
    const editrole = document.querySelector("#editrole");
    const isDeceasededit = document.querySelector("#isDeceasededit");
    const deathContaineredit = document.querySelector("#deathContaineredit");
let editnamestatus=false;
      let editidNumberstatus=false;
      let editbirthDatestatus=false;
      let editdiedstatus=false;
      let editnickNamestatus=false;
      let editrolestatus=false;
      let editmemberPhotostatus=false;
    closenewmemberdata.addEventListener("click",()=>{
        newmemberData.classList.add("hidden");
    });
let originalData='';
let updatedData='';
  const data =await getdataFunction();
  console.group(data);
      var cy = cytoscape({
      container: document.getElementById('family-tree-area'),
      elements:data,
      // elements: [
      //   // People
      //   { data: { id: 'same', name: 'Same', nickname: 'Baba Michael', role: 'Root Father', photo: 'http://localhost:8000/project/family-tree/img/one.jfif' }},
      //   { data: { id: 'mary', name: 'Mary', nickname: 'Mama Michael', role: "Same's Wife", photo: 'http://localhost:8000/project/family-tree/img/one.jfif' }},
      //   { data: { id: 'rosemary', name: 'rosemary', nickname: 'Mama Michael', role: "Same's Wife", photo: 'http://localhost:8000/project/family-tree/img/one.jfif' }},

      //   { data: { id: 'michael', name: 'Michael', nickname: '', role: "", photo: 'http://localhost:8000/project/family-tree/img/one.jfif' }},
      //   { data: { id: 'dun', name: 'dun', nickname: '', role: "", photo: 'http://localhost:8000/project/family-tree/img/one.jfif' }},

      //   { data: { id: 'maryann', name: 'maryann', nickname: '', role: "", photo: '' }},

      //   { data: { id: 'ann', name: 'ann', nickname: '', role: "", photo: '' }},
      //   { data: { id: 'ann2', name: 'ann2', nickname: '', role: "", photo: '' }},
      //   { data: { id: 'rose', name: 'rose', nickname: '', role: "", photo: '' }},

      //   { data: { id: 'john', name: 'john', nickname: '', role: "", photo: '' }},
      //   { data: { id: 'ses', name: 'ses', nickname: '', role: "", photo: '' }},
      //   { data: { id: 'jeni', name: 'jeni', nickname: '', role: "", photo: '' }},

      //   { data: { id: 'john2', name: 'john', nickname: '', role: "", photo: '' }},
      //   { data: { id: 'ses2', name: 'ses', nickname: '', role: "", photo: '' }},
      //   { data: { id: 'jeni2', name: 'jeni', nickname: '', role: "", photo: '' }},
      //   //relationship
      //   { data: { source: 'same', target: 'mary' }},
      //   { data: { source: 'same', target: 'rosemary' }},

      //   { data: { source: 'rosemary', target: 'maryann' }},

      //   { data: { source: 'mary', target: 'michael' }},
        
      //   { data: { source: 'mary', target: 'dun' }},

      //   { data: { source: 'dun', target: 'ann' }},
      //   { data: { source: 'dun', target: 'ann2' }},
      //   { data: { source: 'dun', target: 'rose' }},

      //   { data: { source: 'ann', target: 'john' }},
      //   { data: { source: 'ann', target: 'ses' }},
      //   { data: { source: 'ann', target: 'jeni' }},

      //   { data: { source: 'ann2', target: 'john2' }},
      //   { data: { source: 'ann2', target: 'ses2' }},
      //   { data: { source: 'ann2', target: 'jeni2' }},
      // ],

      style: [
        {
          selector: 'edge',
          style: {
            'width': 2,
            'line-color': '#999',
            'curve-style': 'taxi',          // keeps lines straight
            'taxi-direction': 'downward',   // ensures vertical down
            'target-arrow-shape': 'triangle',
            'target-arrow-color': '#999'
          }
        }
      ],

      layout: {
        name: 'breadthfirst',
        directed: true,
        padding: 30,
        spacingFactor: 2,
        orientation: 'horizontal' // Top → Bottom
      },
       //  Control zoom behavior
      //minZoom: 0.5,   //  minimum zoom allowed 
      maxZoom: 5,     //  maximum zoom allowed
     // wheelSensitivity: 1.2 // smoother scroll zoom
    });
// Create HTML cards for each node
cy.nodes().forEach(node => {
   const d = node.data();
   console.log(d)
  const card = document.createElement('div');
  card.className = "card absolute w-full rounded-lg p-2 bg-gray-300";
   
  card.style.position = "absolute";
  card.style.transformOrigin = "center center";
  card.style.transition = "transform 0.1s ease-out, width 0.1s ease-out, height 0.1s ease-out";
  card.innerHTML = `
    <div class="w-full overflow-hidden rounded-md">
      <img class="w-full h-auto rounded" src="${supportsWebP() ? d.photo_webp : d.photo_jpg}" 
         alt="${d.name}" >
    </div>
    <div class="flex flex-col space-y-1 text-xs mt-1">
      <div class="flex flex-row gap-1 flex-wrap break-words text-xs md:text-md"><label>Name:</label><p>${node.data('name')}</p></div>
      <div class="flex flex-row gap-1 flex-wrap "><label class="flex flex-wrap ">Nick name:</label><p>${node.data('nickname')}</p></div>
      <div class="flex flex-row gap-1 flex-wrap "><label class="flex flex-wrap ">role:</label><p>${node.data('role')}</p></div>
      <div  class="w-full flex justify-center md:justify-end"><button id="readMore" class="readMore cursor-pointer bg-white rounded px-2 py-1">Read More</button></div>
    </div>
  `;

  const readMoreBtn = card.querySelector(".readMore");
  readMoreBtn.addEventListener("click", (e) => {
    e.stopPropagation(); // prevent other click events
    console.log("Read more clicked for:", d.name);

    // Example: show in a side panel
      const detailscontainer = document.getElementById('detailscontainer');
      const detailsBox = document.getElementById('details');
    detailscontainer.classList.remove("hidden");
    detailsBox.classList.remove("hidden");
    detailsBox.classList.add("p-4", "rounded-lg", "shadow-lg", "bg-white");
    detailsBox.innerHTML = `
    <div class="w-full overflow-hidden rounded-md">
      <img class="w-full h-auto rounded" src="${supportsWebP() ? d.photo_webp : d.photo_jpg}" 
         alt="${d.name}">
    </div>
    <div class="flex flex-col space-y-1 text-xs mt-1">
      <div class="flex flex-row gap-1"><label>Name:</label><p>${d.name}</p></div>
      <div class="flex flex-row gap-1"><label>Nickname:</label><p>${d.nickname }</p></div>
      <div class="flex flex-row gap-1"><label>Role:</label><p>${d.role }</p></div>
      <div class="flex flex-row gap-1"><label>Bari:</label><p>igi</p></div>
      <div class="flex flex-row gap-1"><label>ID:</label><p>35855794</p></div>
      <div class="flex flex-row gap-1"><label>Email</label><p>@gmail.com</p></div>
      <div class="flex flex-row gap-1"><label>Tel:</label><p>0717700654</p></div>
      <div class="flex flex-row gap-1"><label>location:</label><p>uthiru</p></div>
      
    </div>
    <div class="w-full flex justify-center">
     <button id="editMemberBtn" 
              class="bg-green-400 hover:bg-green-500 text-white rounded px-3 py-1 text-md">Edit</button>
    </div>
    <!-- RELATIONSHIP CONTROLS -->
  <div class="mt-4 p-2 border-t border-gray-300">
    <h4 class="font-semibold text-sm mb-2 text-center">Add Relationship</h4>
    <div class="flex flex-col space-y-2">
      <label for="directionSelect" class="text-xs font-medium">Select direction:</label>
      <select id="directionSelect" class="border border-gray-400 rounded px-2 py-1 text-xs">
        <option value="">-- Choose direction --</option>
        <option value="forward">Forward</option>
        <option value="backward">Backward</option>
      </select>
    </div>

    <div class="flex flex-col space-y-2 mt-2">
      <label for="relationSelect" class="text-xs font-medium">Select Relationship:</label>
      <select id="relationSelect" class="border border-gray-400 rounded px-2 py-1 text-xs">
        <option value="">-- Choose relationship --</option>
      </select>

      <button id="addMemberBtn" 
              class="bg-green-400 hover:bg-green-500 text-white rounded px-3 py-1 text-xs">
         Add Member
      </button>
    </div>
  </div>
    <div class="w-full flex justify-center mt-3">
    <button id="closeDetails" 
            class="bg-green-400 hover:bg-green-500 text-white rounded px-3 py-1 text-sm">
      Close
    </button>
  </div>
    `;
    // Logic: Change available relationships dynamically
const directionSelect = detailsBox.querySelector("#directionSelect");
const relationSelect = detailsBox.querySelector("#relationSelect");
let transientState=false;
directionSelect.addEventListener("change", (e) => {
  const dir = e.target.value;
  relationSelect.innerHTML = ""; // clear old options

  let options = [];

  if (dir === "forward") {
    transientState=true;
    options = [
      { value: "default", text: "--default--" },
      { value: "wife", text: "Wife" },
      { value: "husband", text: "Husband" },
      { value: "child", text: "Child" },
    ];
  } else if (dir === "backward") {
    transientState=true;
    options = [
      { value: "default", text: "--default--" },
      { value: "mother", text: "Mother" },
      { value: "father", text: "Father" },
      { value: "other", text: "Other" },
    ];
  } else {
    transientState=false;
    options = [{ value: "", text: "-- Choose relationship --" }];
  }

  // repopulate dropdown
  options.forEach(opt => {
    const optionEl = document.createElement("option");
    optionEl.value = opt.value;
    optionEl.textContent = opt.text;
    relationSelect.appendChild(optionEl);
  });
});
//edit user data 
const editMemberBtn = detailsBox.querySelector("#editMemberBtn");
const editnodeData = document.querySelector("#editnodeData");

const editpreview = document.querySelector("#editpreview");
const editname = document.querySelector("#editname");
const editidNumber = document.querySelector("#editidNumber");
const editbirthDate = document.querySelector("#editbirthDate");
const editdied = document.querySelector("#editdied");
const editnickName = document.querySelector("#editnickName");
const editrole = document.querySelector("#editrole");
editMemberBtn.addEventListener("click", () => {
    editnodeData.classList.remove("hidden");
    //pass data
   
    const photoUrl = supportsWebP() && d.photo_webp
    ? d.photo_webp
    : d.photo_jpg;

      // Check if there’s an image URL
    if (photoUrl && photoUrl.trim() !== "") {
      editpreview.src = photoUrl;
      editpreview.classList.remove("hidden");  
      console.log("empry urlff",photoUrl);
    } else {
      editpreview.src = "";
      editpreview.classList.add("hidden"); 
      console.log("empry url",photoUrl);
    }
    editname.value=d.name;
    editidNumber.value=d.idNumber;
    editbirthDate.value=d.birthDate;
    editdied.value=d.died;
    editnickName.value=d.nickname;
    editrole.value=d.role;
 originalData = {
                editname: d.name,
                editidNumber: d.idNumber,
                editbirthDate: d.birthDate,
                editdied: d.died,
                editnickName: d.nickname,
                editrole: d.role
            };
  });
    const closeBtn = detailsBox.querySelector("#closeDetails");
    const closeeditdata = document.querySelector("#closeeditdata");
    const saveChangesBtn = document.querySelector("#saveChangesBtn");
  closeBtn.addEventListener("click", () => {
    detailsBox.classList.add("hidden");
    detailscontainer.classList.add("hidden");
  });
  closeeditdata.addEventListener("click", () => {
     editnodeData.classList.add("hidden");
  });


      editmemberPhoto.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
          editpreview.src = URL.createObjectURL(file);
          editpreview.classList.remove("hidden");
          editmemberPhotostatus=true;
        }else{
            editpreview.src="";
            editpreview.classList.add("hidden");
            editmemberPhotostatus=false;
        }
      });
      
      editname.addEventListener('change', () => {
        if(editname.value.trim()===""){
            editnamestatus=false;
        }else{
            editnamestatus=true;
        }
      });
      editidNumber.addEventListener('change', () => {
        if(editidNumber.value.trim()===""){
            editidNumberstatus=false;
        }else{
            editidNumberstatus=true;
        }
      });
      editbirthDate.addEventListener('change', () => {
        if(editbirthDate.value.trim()===""){
            editbirthDatestatus=false;
        }else{
            editbirthDatestatus=true;
        }
      });
      editdied.addEventListener('change', () => {
        if(editdied.value.trim()===""){
            editdiedstatus=false;
        }else{
            editdiedstatus=true;
        }
      });
      editnickName.addEventListener('change', () => {
        if(editnickName.value.trim()===""){
            editnickNamestatus=false;
        }else{
            editnickNamestatus=true;
        }
      });
      editrole.addEventListener('change', () => {
        if(editrole.value.trim()===""){
            editrolestatus=false;
        }else{
            editrolestatus=true;
        }
      });

  saveChangesBtn.addEventListener("click",()=>{
    console.log("savechanges clicked",editdied.value);
    updatedData= {
                editname: sanitize(editname.value),
                editidNumber: sanitize(editidNumber.value),
                editbirthDate: sanitize(editbirthDate.value) === "" 
                ? "0000-00-00 00:00:00" : sanitize(editdied.value),
                editdied: sanitize(editdied.value) === "" 
                ? "0000-00-00 00:00:00" : sanitize(editdied.value),
                editnickName: sanitize(editnickName.value),
                editrole: sanitize(editrole.value)
            };
            const hasChanges = Object.keys(originalData).some(
                key => originalData[key] !== updatedData[key]
            );
            if(hasChanges &&(editnamestatus || editidNumberstatus || editbirthDatestatus || editdiedstatus || editnickNamestatus || editrolestatus || editmemberPhotostatus)){
              console.log("has changed");
                      async function editmemberFunction() {
               const formData = new FormData();

            formData.append("editmemberStatus", true);
            formData.append("treeId", d.treeId);
            formData.append("userId", d.id);
            formData.append("csrtfToken", editcsrtfTokenid.value);
            formData.append("editname", sanitize(editname.value));
            formData.append("editidNumber", sanitize(editidNumber.value));
            formData.append(
              "editbirthDate",
              sanitize(editbirthDate.value) === "" ? "0000-00-00 00:00:00" : sanitize(editbirthDate.value)
            );
            formData.append(
              "editdied",
              sanitize(editdied.value) === "" ? "0000-00-00 00:00:00" : sanitize(editdied.value)
            );
            formData.append("editnickName", sanitize(editnickName.value));
            formData.append("editrole", sanitize(editrole.value));

            
            if (editmemberPhotostatus && editmemberPhoto.files[0]) {
              formData.append("photo", editmemberPhoto.files[0]);
            } else {
              formData.append("photo", ""); 
            }
             const response = await fetch('insertData.php',{
                method:"POST",
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
                      addNewMemberBtn,
                      url:""
                    });
                }else{
                  showAlert({
                    alertMessage,
                    message: result.message,
                    saveChangesBtn,
                  });
                }
             }
             catch(jsonErr){
                console.log("response error:" + jsonErr);
             }
          }
          editmemberFunction();

            }else{
              console.log(editnamestatus,editidNumberstatus,editbirthDatestatus,editdiedstatus,editnickNamestatus,editrolestatus,editmemberPhotostatus);
              showAlert({
                alertMessage,
                message: "Add some changes ather than photo" ,
                saveChangesBtn,
              });
            }
            console.log("original data:",originalData);
            console.log(" ");
            console.log("updated data:",updatedData);
  });
    newmemberPhoto.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
      newMemberpreview.src = URL.createObjectURL(file);
      newMemberpreview.classList.remove("hidden");
    }else{
        newMemberpreview.src="";
        newMemberpreview.classList.add("hidden");
    }
  });
  //adnew member branch
  
  addMemberBtn.addEventListener("click", () => {
    
    //transer data to ad m ember form
    console.log(d.id);
    if(transientState==true && relationSelect.value!= "default"){
      newmemberData.classList.remove("hidden");
      const connectionNode = document.querySelector("#connectionNode");
      const connectionUnid = document.querySelector("#connectionUnid");
      const connectionContinumRelationship = document.querySelector("#connectionContinumRelationship");
      const connectionDirection = document.querySelector("#connectionDirection");
      connectionNode.value=d.treeId;
      connectionUnid.value=d.id;
      connectionContinumRelationship.value=relationSelect.value;
      connectionDirection.value=directionSelect.value;
    }else{
      showTimedAlert({
        alertMessage,
        message: "choose relationship " ,
        addMemberBtn,
      });
    }
  });
  });
  //get data and move foward to add member
   //store reference
  nodeCards[node.id()] = card;

  document.getElementById('family-tree-area').appendChild(card);

  // position the card based on the node
  const pos = node.renderedPosition();
  card.style.left = pos.x + 'px';
  card.style.top = pos.y + 'px';

  // update position on zoom/move
  cy.on('pan zoom position', () => {
    const p = node.renderedPosition();
    card.style.left = p.x + 'px';
    card.style.top = p.y + 'px';
  });

});

    //updateCardPositions(); // initial render
function updateCardPositions() {
  const zoom = cy.zoom();
  const pan = cy.pan();

  cy.nodes().forEach(node => {
    const pos = node.renderedPosition();
    const card = nodeCards[node.id()];
    if (!card) return;

    // Ensure the card centers on the node
    card.style.left = `${pos.x}px`;
    card.style.top = `${pos.y}px`;

    // Apply zoom scaling to the card itself (instead of resizing width manually)
    card.style.transform = `translate(-50%, -50%) scale(${zoom})`;
    card.style.transformOrigin = "center center";
console.log(zoom)
    // Hide images when too small
    if (zoom < 0.7) {
      card.querySelector("img").style.display = "none";
      const baseWidth = 200;  // your default card width
      card.style.width = `${baseWidth }px`;
    } else {
      card.querySelector("img").style.display = "block";
      const baseWidth = 140;  // your default card width
      card.style.width = `${baseWidth }px`;
    }

    // Optional: reduce text font size slightly as zoom decreases
    const textSize = Math.max(8, 10 * zoom);
    card.style.fontSize = `${textSize}px`;
  });
}

let lastUpdate = 0;
// update on zoom/pan/render
cy.on('zoom pan render', ()=>{
  const now = Date.now();
  if(now - lastUpdate >30){
    updateCardPositions();
    lastUpdate=now;
  }
});
updateCardPositions(); // initial render
//member add form 
  addNewMemberBtn.addEventListener("click",e=>{
      console.log(connectionUnid.value,connectionContinumRelationship.value,connectionDirection.value)
    if(newMembernickName.value=="" && newmembersname.value==""){
        showAlert({
        alertMessage,
        message: "Please enter at least a nickname or name." ,
        addNewMemberBtn,
      });
    }else{
        async function addnewmemberFunction() {
                const csrtfTokenValue = csrtfTokenid.value;
               // console.log(csrtfTokenValue)
                const newmembersname = document.querySelector("#newmembersname");
                const newMemberidNumber = document.querySelector("#newMemberidNumber");
                const newMemberbirthDate = document.querySelector("#newMemberbirthDate");
                const newMemberdied = document.querySelector("#newMemberdied");
                const newMembernickName = document.querySelector("#newMembernickName");
                const postData ={
                    addnewMemberStatus:true,
                    newmembersname:sanitize(newmembersname.value),
                    newMemberidNumber:sanitize(newMemberidNumber.value),
                    newMemberdied:sanitize(newMemberdied.value),
                    newMemberbirthDate:sanitize(newMemberbirthDate.value),
                    newMembernickName:sanitize(newMembernickName.value),
                    connectionNode:sanitize(connectionNode.value),
                    connectionUnid:sanitize(connectionUnid.value),
                    connectionContinumRelationship:sanitize(connectionContinumRelationship.value),
                    connectionDirection:sanitize(connectionDirection.value),
                    csrtfToken:sanitize(csrtfTokenValue)
                };
                const formData = new FormData();

                for (const key in postData) {
                formData.append(key, postData[key]);
                }

                // Add file if available
                if (newmemberPhoto.files[0]) {
                formData.append("photo", newmemberPhoto.files[0]);
                }
                
             const response = await fetch('insertData.php',{
                method:"POST",
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
                      addNewMemberBtn,
                      url:""
                    });
                }else{
                  showAlert({
                    alertMessage,
                    message: result.message,
                    addNewMemberBtn,
                  });
                }
             }
             catch(jsonErr){
                console.log("response error:" + jsonErr);
             }
          }
          addnewmemberFunction();
    }
  });



  isDeceasednewMember.addEventListener('change', () => {
    if (isDeceasednewMember.checked) {
      newMemberdeathContainer.classList.remove('hidden');
    } else {
      newMemberdeathContainer.classList.add('hidden');
      document.getElementById('died').value = ''; // clear value if unchecked
    }
  });

      isDeceasededit.addEventListener('change', () => {
        
    if (isDeceasededit.checked) {
      deathContaineredit.classList.remove('hidden');
    } else {
      deathContaineredit.classList.add('hidden');
      document.getElementById('editdied').value = ''; // clear value if unchecked
    }
  });
});