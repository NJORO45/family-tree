
addEventListener("DOMContentLoaded",()=>{
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
      minZoom: 0.5,   // ← minimum zoom allowed (default is 0.1)
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
      <div  class="w-full flex justify-end"><button id="readMore" class="readMore cursor-pointer bg-white rounded px-2 py-1">Read More</button></div>
    </div>
  `;

  const readMoreBtn = card.querySelector(".readMore");
  readMoreBtn.addEventListener("click", (e) => {
    e.stopPropagation(); // prevent other click events
    console.log("Read more clicked for:", d.name);

    // Example: show in a side panel
      const detailsBox = document.getElementById('details');
    detailsBox.classList.remove("hidden");
    detailsBox.innerHTML = `
    <div class="w-full overflow-hidden rounded-md">
      <img class="w-full h-auto rounded" src="${d.photo}" alt="photo">
    </div>
    <div class="flex flex-col space-y-1 text-xs mt-1">
      <div class="flex flex-row gap-1"><label>Name:</label><p>${d.name}</p></div>
      <div class="flex flex-row gap-1"><label>Nickname:</label><p>${d.nickname }</p></div>
      <div class="flex flex-row gap-1"><label>Bari:</label><p>igi</p></div>
      <div class="flex flex-row gap-1"><label>ID:</label><p>35855794</p></div>
      <div class="flex flex-row gap-1"><label>Email</label><p>@gmail.com</p></div>
      <div class="flex flex-row gap-1"><label>Tel:</label><p>0717700654</p></div>
      <div class="flex flex-row gap-1"><label>location:</label><p>uthiru</p></div>
      
    </div>
    <div class="w-full flex justify-center mt-3">
    <button id="closeDetails" 
            class="bg-green-500 hover:bg-green-400 text-white rounded px-3 py-1 text-sm">
      Close
    </button>
  </div>
    `;
    const closeBtn = detailsBox.querySelector("#closeDetails");
  closeBtn.addEventListener("click", () => {
    detailsBox.classList.add("hidden");
  });
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
    //  Function to update card positions + scaling
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

    //  Update on zoom/pan/render
    //cy.on('render zoom pan position', updateCardPositions);
    //updateCardPositions(); // initial render

});