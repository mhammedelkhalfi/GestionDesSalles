<?php
// Inclure la connexion à la base de données
include 'connexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM salle WHERE num_salle = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header('Location: gestion_salles.php?success=deleted');
    } else {
        echo "Erreur lors de la suppression de la salle.";
    }
} else {
    echo "Aucun ID fourni.";
}
?>
