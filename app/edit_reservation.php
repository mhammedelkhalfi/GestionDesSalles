<?php
// Inclure le fichier de connexion MySQL
include 'connexion.php';  // Assurez-vous que le chemin d'inclusion est correct

// Démarrer la session
session_start();

// Vérifier si l'employé est connecté
if (!isset($_SESSION['employe_id'])) {
    header('Location: login.php');
    exit();
}

// Connexion à la base de données (mysqli)
$conn = new mysqli($host, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Vérifier si un ID de réservation est passé en paramètre
if (isset($_GET['id'])) {
    $reservation_id = $_GET['id'];

    // Récupérer les informations de la réservation à modifier
    $query = "SELECT * FROM reservation WHERE num_reservation = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $reservation = $result->fetch_assoc();

    // Récupérer les salles disponibles
    $query_salles = "SELECT * FROM salle";
    $result_salles = $conn->query($query_salles);
    $salles = [];
    while ($salle = $result_salles->fetch_assoc()) {
        $salles[] = $salle;
    }

    // Vérifier si le formulaire a été soumis pour mettre à jour la réservation
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $num_salle = $_POST['num_salle'];
        $date_reservation = $_POST['date_reservation'];
        $heure_debut = $_POST['heure_debut'];
        $duree = $_POST['duree'];

        // Mettre à jour la réservation
        $update_query = "UPDATE reservation SET num_salle = ?, date_reservation = ?, heure_debut = ?, duree = ? WHERE num_reservation = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("issii", $num_salle, $date_reservation, $heure_debut, $duree, $reservation_id);

        if ($update_stmt->execute()) {
            header('Location: dashboard.php'); // Rediriger vers le dashboard après mise à jour
            exit();
        } else {
            echo "Erreur lors de la mise à jour de la réservation.";
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Aucune réservation sélectionnée.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la réservation</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Modifier la réservation</h2>
        <form action="edit_reservation.php?id=<?php echo $reservation['num_reservation']; ?>" method="POST">
            <div class="form-group">
                <label for="num_salle">Salle</label>
                <select class="form-control" id="num_salle" name="num_salle">
                    <?php foreach ($salles as $salle): ?>
                        <option value="<?php echo $salle['num_salle']; ?>" <?php echo $salle['num_salle'] == $reservation['num_salle'] ? 'selected' : ''; ?>>
                            <?php echo $salle['type_salle']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="date_reservation">Date de réservation</label>
                <input type="date" class="form-control" id="date_reservation" name="date_reservation" value="<?php echo $reservation['date_reservation']; ?>" required>
            </div>

            <div class="form-group">
                <label for="heure_debut">Heure de début</label>
                <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?php echo $reservation['heure_debut']; ?>" required>
            </div>

            <div class="form-group">
                <label for="duree">Durée (en minutes)</label>
                <input type="number" class="form-control" id="duree" name="duree" value="<?php echo $reservation['duree']; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour la réservation</button>
        </form>
    </div>
</body>
</html>
