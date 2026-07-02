<?php
session_start();
$conn = new mysqli("localhost", "root", "", "to_list");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];
  

  $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows == 1) {
    $stmt->bind_result($id, $hashed);
    $stmt->fetch();
    if (password_verify($password, $hashed)) {
      $_SESSION['user_id'] = $id;
      $_SESSION['user'] = $email;
      header("Location: list.php");
      exit();
    } else {
      $error = "Mot de passe incorrect.";
    }
  } else {
    $error = "Email introuvable.";
  }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h3>Connexion </h3>
    <?php if (isset($error)) echo "<div class='alert'>$error</div>"; ?>
    <form method="post">
      <label><i class="fa-solid fa-circle-user"> </i> Email</label>
      <input type="email" name="email" required>
      
      <label><i class="fa-solid fa-key"></i> Mot de passe</label>
      <input type="password" name="password" required>
      
      <button type="submit">Se connecter</button>
      <a href="cree.php" class="btn-link">Créer un compte</a>
    </form>
  </div>
</body>
</html>