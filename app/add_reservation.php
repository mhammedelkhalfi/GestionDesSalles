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
        // Calcul de l'heure de fin
        $heure_fin = date("H:i", strtotime($heure) + ($duree * 60));

        // Vérification de la disponibilité de la salle
$sql = "SELECT * FROM reservation 
WHERE num_salle = :salle 
AND date_reservation = :date 
AND (
    (:heure BETWEEN heure_debut AND heure_fin) OR
    (:heure_fin BETWEEN heure_debut AND heure_fin) OR
    (heure_debut BETWEEN :heure AND :heure_fin) OR
    (heure_fin BETWEEN :heure AND :heure_fin)
)";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':salle', $salle);
$stmt->bindParam(':date', $date);
$stmt->bindParam(':heure', $heure);
$stmt->bindParam(':heure_fin', $heure_fin);
$stmt->execute();

        // Si la salle est déjà réservée, afficher un message
if ($stmt->rowCount() > 0) {
    // Redirection vers le tableau de bord avec un message d'erreur
    header('Location: dashboard.php?error=salle_occupee');
    exit();
} else {
    // Préparer la requête d'insertion de la réservation
    $sql = "INSERT INTO reservation (num_employe, num_salle, date_reservation, heure_debut, duree, heure_fin) 
            VALUES (:employe_id, :salle, :date, :heure, :duree, :heure_fin)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':employe_id', $employe_id);
    $stmt->bindParam(':salle', $salle);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':heure', $heure);
    $stmt->bindParam(':duree', $duree);
    $stmt->bindParam(':heure_fin', $heure_fin);

    // Exécuter la requête d'insertion
    if ($stmt->execute()) {
        header('Location: dashboard.php?success=1');
        exit();
    } else {
        echo "Erreur lors de l'ajout de la réservation.";
    }
}
    } else {
        echo "Tous les champs sont requis.";
    }
}
?>
