<?php
// Inclure la connexion à la base de données
include 'connexion.php';

// Démarrer la session
session_start();

// Vérifier si l'employé est connecté
if (!isset($_SESSION['employe_id'])) {
    header('Location: login.php');
    exit();
}

// Récupérer les informations du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $salle = $_POST['salle'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $duree = $_POST['duree'];
    $employe_id = $_SESSION['employe_id'];

    // Vérifier que les champs sont remplis
    if (!empty($salle) && !empty($date) && !empty($heure) && !empty($duree)) {
        // Préparer la requête d'insertion
        $sql = "INSERT INTO reservation (num_employe, num_salle, date_reservation, heure_debut, duree) 
                VALUES (:employe_id, :salle, :date, :heure, :duree)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':employe_id', $employe_id);
        $stmt->bindParam(':salle', $salle);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':heure', $heure);
        $stmt->bindParam(':duree', $duree);

        // Exécuter la requête
        if ($stmt->execute()) {
            // Redirection vers la page des réservations avec un message de succès
            header('Location: reservations.php?success=1');
            exit();
        } else {
            echo "Erreur lors de l'ajout de la réservation.";
        }
    } else {
        echo "Tous les champs sont requis.";
    }
}
?>
