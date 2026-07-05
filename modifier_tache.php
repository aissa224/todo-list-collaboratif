<?php
// modifier_tache.php
// Affiche un petit formulaire pour éditer le texte d'une tâche,
// puis enregistre la modification.
session_start();
$conn = new mysqli("localhost", "root", "", "to_list");

if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// --- Traitement du formulaire (enregistrement) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = intval($_POST['id']);
  $texte = trim($_POST['texte']);

  if ($id > 0 && $texte !== "") {
    $stmt = $conn->prepare(
      "UPDATE taches SET texte = ? WHERE id = ? AND user_id = ?"
    );
    $stmt->bind_param("sii", $texte, $id, $user_id);
    $stmt->execute();
    $stmt->close();
  }

  $conn->close();
  header("Location: list.php");
  exit();
}

// --- Affichage du formulaire (on récupère la tâche à modifier) ---
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT texte FROM taches WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$stmt->bind_result($texte);

if (!$stmt->fetch()) {
  // Tâche introuvable ou n'appartient pas à l'utilisateur
  header("Location: list.php");
  exit();
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier la tâche</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h3><i class="fa-solid fa-pen"></i> Modifier la tâche</h3>
    <form method="post">
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
      <label>Texte de la tâche</label>
      <input type="text" name="texte" value="<?php echo htmlspecialchars($texte); ?>" required>
      <button type="submit">Enregistrer</button>
      <a href="list.php" class="btn-link">Annuler</a>
    </form>
  </div>
</body>
</html>
