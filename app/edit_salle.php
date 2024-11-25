<?php
// Inclure la connexion à la base de données
include 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num_salle = $_POST['num_salle'];  // Récupérer l'ID de la salle
    $type_salle = $_POST['type_salle'];

    if (!empty($type_salle)) {
        // Mise à jour de la salle
        $updateSql = "UPDATE salle SET type_salle = :type_salle WHERE num_salle = :num_salle";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->bindParam(':type_salle', $type_salle);
        $updateStmt->bindParam(':num_salle', $num_salle);

        if ($updateStmt->execute()) {
            header('Location: gestion_salles.php?success=updated');
            exit();
        } else {
            echo "Erreur lors de la mise à jour de la salle.";
        }
    } else {
        echo "Le type de salle est requis.";
    }
}

?>
