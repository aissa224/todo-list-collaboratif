<?php
// supprimer_tache.php
// Supprime définitivement une tâche appartenant à l'utilisateur connecté.
session_start();
$conn = new mysqli("localhost", "root", "", "to_list");

if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
  $stmt = $conn->prepare(
    "DELETE FROM taches WHERE id = ? AND user_id = ?"
  );
  $stmt->bind_param("ii", $id, $user_id);
  $stmt->execute();
  $stmt->close();
}

$conn->close();
header("Location: list.php");
exit();
