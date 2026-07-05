<?php
// terminer_tache.php
// Marque une tâche comme terminée (ou la remet en cours si elle l'était déjà).
session_start();
$conn = new mysqli("localhost", "root", "", "to_list");

if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
  // On vérifie que la tâche appartient bien à l'utilisateur connecté
  // avant de la modifier (sécurité : évite qu'un utilisateur modifie
  // les tâches d'un autre en changeant l'id dans l'URL).
  $stmt = $conn->prepare(
    "UPDATE taches SET complete = NOT complete WHERE id = ? AND user_id = ?"
  );
  $stmt->bind_param("ii", $id, $user_id);
  $stmt->execute();
  $stmt->close();
}

$conn->close();
header("Location: list.php");
exit();
