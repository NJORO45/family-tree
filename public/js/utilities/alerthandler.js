
export function showAlert({
  alertMessage, 
  message, 
  addNewNodeBtn = null
  }) {
  const p = alertMessage.querySelector("p") || document.createElement("p");
  p.textContent = message;
  if (!alertMessage.contains(p)) alertMessage.appendChild(p);

  // Show alert
  alertMessage.classList.remove("hidden");
  alertMessage.classList.add("flex", "animate-slide-down");

  // Enable button if provided
  if (addNewNodeBtn) addNewNodeBtn.disabled = false;

  // Animate hide
  setTimeout(() => {
    alertMessage.classList.remove("animate-slide-down");
    alertMessage.classList.add("animate-slide-up");
  }, 2000);

  setTimeout(() => {
    alertMessage.classList.add("hidden", "animate-slide-down");
    alertMessage.classList.remove("flex", "animate-slide-up");
  }, 2400);
}
// public/js/utilities/uiHandler.js
export function showTimedAlert({
  alertMessage,
  message,
  addNewNodeBtn = null,
  nameInput = null,
  idInput = null,
  newNodeData = null,
  url = null,
}) {
  // Disable button during process
  if (addNewNodeBtn) addNewNodeBtn.disabled = true;

  // Display message
  const p = alertMessage.querySelector("p") || document.createElement("p");
  p.textContent = message;
  if (!alertMessage.contains(p)) alertMessage.appendChild(p);

  // Show alert
  alertMessage.classList.remove("hidden");
  alertMessage.classList.add("flex", "animate-slide-down");

  // Animate hide after delay
  setTimeout(() => {
    alertMessage.classList.remove("animate-slide-down");
    alertMessage.classList.add("animate-slide-up");
  }, 2000);

  // Close modal & clear fields
  setTimeout(() => {
    alertMessage.classList.add("hidden");
    alertMessage.classList.remove("flex", "animate-slide-down", "animate-slide-up");

    if (nameInput) nameInput.value = "";
    if (idInput) idInput.value = "";
    if (addNewNodeBtn) addNewNodeBtn.disabled = false;
  }, 2400);

  // Optionally hide modal or redirect
  setTimeout(() => {
    if (newNodeData) newNodeData.classList.add("hidden");
    if (url && url !== "") {
      window.location.href = url; // go to given page
    } else {
      window.location.reload(); // reload same page
    }
  }, 2500);
}

export async function refreshCsrfTokens() {
    const csrfInputs = document.querySelectorAll(".csrtfToken");
    const basePath = window.location.pathname.includes('/php/')
  ? '../../includes/csrfTokenGenerator.php'
  : '../includes/csrfTokenGenerator.php';
    try {
        const response = await fetch(basePath, {
            method: "GET",
            headers: { "Accept": "application/json" }
        });

        const text = await response.text();
        console.log(text);

        try {
            const results = JSON.parse(text);
            if (results && results.csrfToken) {
                csrfInputs.forEach(input => {
                    input.value = results.csrfToken;
                });
            }
        } catch (jsonErr) {
            console.error("JSON parse error:", jsonErr);
        }
    } catch (err) {
        console.error("Fetch error:", err);
    }
}