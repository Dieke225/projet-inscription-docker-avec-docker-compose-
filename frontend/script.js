document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");
  const nomInput = document.querySelector('input[name="nom"]');
  const emailInput = document.querySelector('input[name="email"]');
  const messageBox = document.createElement("p");

  messageBox.style.color = "red";
  messageBox.style.fontWeight = "bold";
  form.appendChild(messageBox);

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    messageBox.textContent = "";

    const nom = nomInput.value.trim();
    const email = emailInput.value.trim();

    if (!nom || !email) {
      messageBox.textContent = "Veuillez remplir tous les champs.";
      return;
    }

    try {
      const response = await fetch("http://localhost:8081/api.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `nom=${encodeURIComponent(nom)}&email=${encodeURIComponent(email)}`
      });

      const result = await response.json(); // ✅ backend renvoie du JSON
      messageBox.style.color = result.success ? "green" : "red";
      messageBox.textContent = result.message;

      if (result.success) {
        nomInput.value = "";
        emailInput.value = "";
        afficherInscriptions(); // ✅ rafraîchir la liste
      }

    } catch (error) {
      messageBox.textContent = "Erreur de connexion au serveur ❌";
    }
  });

  // Charger la liste dès l’ouverture
  afficherInscriptions();

  // 🔹 Rafraîchir automatiquement toutes les 10 secondes
  setInterval(afficherInscriptions, 10000);
});

// Fonction pour afficher les inscriptions
async function afficherInscriptions() {
  try {
    const response = await fetch("http://localhost:8081/list.php");
    const data = await response.json();

    const container = document.getElementById("inscriptions");
    container.innerHTML = "";

    data.forEach(user => {
      const item = document.createElement("p");
      
    const date = new Date(user.created_at);
      const dateLocale = date.toLocaleString("fr-FR", {
        day: "2-digit",
        month: "long",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit"
      });

      item.textContent = `${user.nom} (${user.email}) inscrit le ${dateLocale}`;
      container.appendChild(item);
    });
  } catch (error) {
    console.error("Erreur lors du chargement des inscriptions ❌", error);
  }
}
