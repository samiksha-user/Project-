function loadPage(page) {
  // Highlight active menu
  document.querySelectorAll(".nav-link").forEach(link => {
    link.classList.remove("active");
  });

  event.target.classList.add("active");

  // Load PHP content
  fetch("backend/" + page + ".php")
    .then(response => response.text())
    .then(data => {
      document.getElementById("content").innerHTML = data;
    });
}

// Load dashboard by default
window.onload = () => loadPage("dashboard");
