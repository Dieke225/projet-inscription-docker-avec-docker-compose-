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
    const champsManquants = [];

    if (!nom) champsManquants.push("le nom");
    if (!email) champsManquants.push("l’adresse e‑mail");

    if (champsManquants.length > 0) {
      messageBox.textContent = `Veuillez remplir ${champsManquants.join(" et ")}.`;
      return;
    }

    try {
      const response = await fetch("http://localhost:8081/api.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `nom=${encodeURIComponent(nom)}&email=${encodeURIComponent(email)}`
      });

      const result = await response.text();
      messageBox.style.color = result.includes("✅") ? "green" : "red";
      messageBox.textContent = result;

      // Rafraîchir la liste après ajout
      afficherInscriptions();

    } catch (error) {
      messageBox.textContent = "Erreur de connexion au serveur ❌";
    }
  });

  // Charger la liste dès l’ouverture
  afficherInscriptions();
});

// Fonction pour afficher les inscriptions
async function afficherInscriptions() {
  const response = await fetch("http://localhost:8081/list.php");
  const data = await response.json();

  const container = document.getElementById("inscriptions");
  container.innerHTML = "";

  data.forEach(user => {
    const item = document.createElement("p");
    item.textContent = `${user.nom} (${user.email}) inscrit le ${user.date_inscription}`;
    container.appendChild(item);
  });
}
