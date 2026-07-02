<?php
session_start();
$conn = new mysqli("localhost", "root", "", "to_list");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nom = $_POST['nom'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirm = $_POST['confirm_password'];

  if ($password !== $confirm) {
    $error = "Les mots de passe ne correspondent pas.";
  } else {
    // Vérifie si l'utilisateur existe déjà
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
      $error = "Cet email est déjà utilisé.";
    } else {
      $hashed = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO users (nom, email, password) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $nom, $email, $hashed);
      if ($stmt->execute()) {
        header("Location: index.php");
        exit();
      } else {
        $error = "Erreur lors de l'inscription.";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Inscription</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h3>Créer un compte</h3>
    <?php if (isset($error)) echo "<div class='alert'>$error</div>"; ?>
    <form method="post">
      <label>Nom complet</label>
      <input type="text" name="nom" required>
      
      <label>Email</label>
      <input type="email" name="email" required>
      
      <label>Mot de passe</label>
      <input type="password" name="password" required>
      
      <label>Confirmer le mot de passe</label>
      <input type="password" name="confirm_password" required>
      
      <button type="submit">S'inscrire</button>
      <a href="index.php" class="btn-link">Se connecter</a>
    </form>
  </div>
</body>
</html>