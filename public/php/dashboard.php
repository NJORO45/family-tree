<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../main.css">
    <!--favicon -->
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css"  />
      <script src="https://cdnjs.cloudflare.com/ajax/libs/cytoscape/3.24.0/cytoscape.min.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    #family-tree-area {
      width: 100%;
      height: 90vh;
      border: 2px solid #ccc;
    }
    #details {
      padding: 1rem;
      border-top: 2px solid #ccc;
      background: #f9f9f9;
      height: 10vh;
      overflow-y: auto;
    }
    .highlight {
      background-color: yellow;
    }
   #family-tree-area {
      width: 100%;
      height: 100vh;
      position: relative;
      background: #f9fafb;
    }
    .card {
      position: absolute;
      transform: translate(-50%, -50%);
      pointer-events: auto; /* let you click buttons */
      width: 200px;
      transition: transform 0.1s ease-out;
    }
  </style>
</head>
<body class="overflow-hidden">
    <!--alert message-->
   <div id="alertMessage" class="fixed z-50 left-1/2 -translate-x-1/2 bg-red hidden bg-orange-300 mt-2 rounded-lg shadow-xl px-3 py-1 gap-1 ">
       <i class="ri-error-warning-fill text-xl"></i>
       <p>alert message</p>
   </div>
   <div>
    
   </div>
   <!-- Top Navigation Bar -->
<nav class="w-full bg-white shadow-md px-2 md:px-6 py-3 flex justify-between gap-1 items-center fixed z-20">

  <!-- Left: Project Name or Logo -->
  <div class="text-sm md:text-2xl font-semibold text-green-600">
    FamilyTree<span class="text-gray-600">.io</span>
  </div>

  <!-- Middle: Search -->
  <div class="flex items-center w-1/3">
    <input type="text" placeholder="Search by name or nickname..."
           class="w-full border border-gray-300 rounded-full px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400">
  </div>
<!-- Add Tree Button -->
<button class="ml-4">
  <i class="ri-add-line text-xl  bg-green-500 hover:bg-green-400 text-white rounded-full  shadow-lg"></i>
</button>

  <!-- Right: User Menu -->
  <div class="relative" id="userMenu">
    <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none ">
        <i class="ri-user-line w-10 h-10 rounded-full border-2 border-green-400 flex items-center justify-center text-lg"></i>
      <span class="hidden md:block text-gray-700 font-medium">John</span>
    </button>

    <!-- Dropdown Menu -->
    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100">
      <a href="#" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Profile</a>
      <a href="#" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Settings</a>
      <div class="border-t border-gray-200 my-1"></div>
      <a href="#" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Logout</a>
    </div>
    <img src="/img/one.jfif" alt="">
  </div>
</nav>
<!--node area-->
<div id="family-tree-area" class="z-10"></div>

<div class="max-w-sm rounded-lg p-2 bg-gray-300">
  <div class="w-full height-auto overflow-hidden">
    <img class="w-full height-auto" src="http://localhost:8000/project/family-tree/img/one.jfif" alt="">
  </div>
  <div class="flex flex-col space-y-2">
    <div class="flex flex-row flex-nowrap gap-1">
      <label for="">name:</label>
      <p>samuel njoroge mwangi  mwangi mwangi mwangi</p>
    </div>
    <div class="flex flex-row flex-nowrap gap-1">
      <label for="">nick name:</label>
      <p>mama brian</p>
    </div>
    <div class="w-full flex justify-center">
      <button class="cursor-pointer">Read More</button>
    </div>
  </div>
</div>
<div id="details" class="p-4 border-t bg-gray-100">Click a member to see details...</div>
 <script>
    var cy = cytoscape({
      container: document.getElementById('family-tree-area'),

      elements: [
        // People
        { data: { id: 'same', name: 'Same', nickname: 'Baba Michael', role: 'Root Father', photo: 'http://localhost:8000/project/family-tree/img/one.jfif' }},
        { data: { id: 'mary', name: 'Mary', nickname: 'Mama Michael', role: "Same's Wife", photo: 'http://localhost:8000/project/family-tree/img/one.jfif' }},
        { data: { id: 'rosemary', name: 'rosemary', nickname: 'Mama Michael', role: "Same's Wife", photo: 'http://localhost:8000/project/family-tree/img/one.jfif' }},

        { data: { id: 'michael', name: 'Michael', nickname: '', role: "", photo: 'http://localhost:8000/project/family-tree/img/one.jfif' }},
        { data: { id: 'dun', name: 'dun', nickname: '', role: "", photo: 'http://localhost:8000/project/family-tree/img/one.jfif' }},

        { data: { id: 'maryann', name: 'maryann', nickname: '', role: "", photo: '' }},

        { data: { id: 'ann', name: 'ann', nickname: '', role: "", photo: '' }},
        { data: { id: 'ann2', name: 'ann2', nickname: '', role: "", photo: '' }},
        { data: { id: 'rose', name: 'rose', nickname: '', role: "", photo: '' }},

        { data: { id: 'john', name: 'john', nickname: '', role: "", photo: '' }},
        { data: { id: 'ses', name: 'ses', nickname: '', role: "", photo: '' }},
        { data: { id: 'jeni', name: 'jeni', nickname: '', role: "", photo: '' }},

        { data: { id: 'john2', name: 'john', nickname: '', role: "", photo: '' }},
        { data: { id: 'ses2', name: 'ses', nickname: '', role: "", photo: '' }},
        { data: { id: 'jeni2', name: 'jeni', nickname: '', role: "", photo: '' }},

        // // Family branch nodes (like marriage bubbles)
        // { data: { id: 'fam1', name: 'Family 1', type: 'family' }},

        // Relationships via family nodes
        // { data: { source: 'mary', target: 'fam1' }},
        { data: { source: 'same', target: 'mary' }},
        { data: { source: 'same', target: 'rosemary' }},

        { data: { source: 'rosemary', target: 'maryann' }},

        { data: { source: 'mary', target: 'michael' }},
        
        { data: { source: 'mary', target: 'dun' }},
        { data: { source: 'dun', target: 'ann' }},
        { data: { source: 'dun', target: 'ann2' }},
        { data: { source: 'dun', target: 'rose' }},

        // { data: { source: 'dun', target: 'fam2' }},
        // { data: { source: 'ann', target: 'fam2' }},

        { data: { source: 'ann', target: 'john' }},
        { data: { source: 'ann', target: 'ses' }},
        { data: { source: 'ann', target: 'jeni' }},

        { data: { source: 'ann2', target: 'john2' }},
        { data: { source: 'ann2', target: 'ses2' }},
        { data: { source: 'ann2', target: 'jeni2' }},
      ],

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
        spacingFactor: 1.5,
        orientation: 'horizontal' // Top → Bottom
      },
       //  Control zoom behavior
      minZoom: 0,   // ← minimum zoom allowed (default is 0.1)
      maxZoom: 20,     // ← maximum zoom allowed
      wheelSensitivity: 0.2 // optional: smoother scroll zoom
    });
// Create HTML cards for each node
cy.nodes().forEach(node => {
   const d = node.data();
  const card = document.createElement('div');
  card.className = "card max-w-sm rounded-lg p-2 bg-gray-300";
  card.innerHTML = `
    <div class="w-full overflow-hidden rounded-md">
      <img class="w-full h-auto rounded" src="${node.data('photo')}" alt="">
    </div>
    <div class="flex flex-col space-y-1 text-xs mt-1">
      <div class="flex flex-row gap-1"><label>Name:</label><p>${node.data('name')}</p></div>
      <div class="flex flex-row gap-1"><label>Nickname:</label><p>${node.data('nickname')}</p></div>
      <div class="w-full flex justify-center"><button class="cursor-pointer bg-white rounded px-2 py-1">Read More</button></div>
    </div>
  `;
    // ✅ Attach click handler right here
  card.addEventListener("click", () => {
    document.getElementById('details').innerHTML = `
      <div style="display:flex; align-items:center; gap:10px;">
        <img src="${d.photo}" width="60" height="60" style="border-radius:50%; object-fit:cover;">
        <div>
          <strong>${d.name}</strong> (${d.nickname || '—'})<br>
          <em>${d.role || '—'}</em><br>
        </div>
      </div>
    `;
  });
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
  const cardDiv = document.querySelectorAll(".card");
  //console.log(cardDiv)
  // cardDiv.forEach(card=>{
  //   card.addEventListener("click",(e)=>{
  //     console.log(e.currentTarget)
  //   });
  // });
 

});
    // 🌀 Function to update card positions + scaling
    function updateCardPositions() {
      const zoom = cy.zoom();

      cy.nodes().forEach(node => {
        const pos = node.renderedPosition();
        const card = nodeCards[node.id()];
        if (!card) return;

        // position each card at node location
        card.style.left = pos.x + 'px';
        card.style.top = pos.y + 'px';

        // ✨ Smooth dynamic scaling based on zoom
        const scale = Math.max(0.7, Math.min(1.1, 1 / zoom));
        card.style.transform = `translate(-50%, -50%) scale(${scale})`;
      });
    }

    // 🔁 Update on zoom/pan/render
    //cy.on('render zoom pan position', updateCardPositions);
    //updateCardPositions(); // initial render

  </script>
</body>
</html>