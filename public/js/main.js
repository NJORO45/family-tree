addEventListener("DOMContentLoaded",()=>{
    const userMenuButton = document.querySelector("#userMenuButton");
    const userDropdown = document.querySelector("#userDropdown");
    userMenuButton.addEventListener("click",()=>{
        userDropdown.classList.toggle("hidden");
    })
});