<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Formulaire d'inscription</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
  <div class="container">
    <div class="row">
      <!-- Colonne gauche : formulaire -->
      <div class="col form-container">
        <h1>Inscription</h1>
        <form method="POST" action="http://api.php">
          <label>Nom :</label>
          <input type="text" name="nom" required>
          <label>Email :</label>
          <input type="email" name="email" required>
          <button type="submit">S'inscrire</button>
        </form>
      </div>

      <!-- Colonne droite : liste -->
      <div class="col list-container">
        <h2>Liste des inscrits</h2>
        <div id="inscriptions"></div>
      </div>
    </div>
  </div>
</body>
</html>
