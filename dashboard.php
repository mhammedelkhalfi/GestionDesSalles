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

// Récupérer les informations de l'employé depuis la session
$nom = isset($_SESSION['nom']) ? $_SESSION['nom'] : '';
$prenom = isset($_SESSION['prenom']) ? $_SESSION['prenom'] : '';
$departement = isset($_SESSION['departement']) ? $_SESSION['departement'] : '';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$employe_id = $_SESSION['employe_id'];

// Connexion à la base de données pour récupérer les réservations de l'employé
$conn = new mysqli($host, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

$sql = "SELECT r.num_reservation, r.date_reservation, r.heure_debut, r.duree, s.type_salle
        FROM reservation r
        JOIN salle s ON r.num_salle = s.num_salle
        WHERE r.num_employe = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employe_id);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si des réservations existent
$reservations = [];
while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réservations</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Gestion des Réservations</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active"><a class="nav-link" href="reservations.php">Réservations</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php">Mon Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Se déconnecter</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Bienvenue, <?php echo htmlspecialchars($prenom . ' ' . $nom); ?></h1>
        <p><strong>Département : </strong><?php echo htmlspecialchars($departement); ?></p>
        <p><strong>Rôle : </strong><?php echo htmlspecialchars($role); ?></p>

        <!-- Bouton pour ouvrir le modal -->
        <button class="btn btn-primary" data-toggle="modal" data-target="#reservationModal">Ajouter une réservation</button>

        <!-- Modal pour ajouter une réservation -->
        <div class="modal fade" id="reservationModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reservationModalLabel">Ajouter une réservation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="add_reservation.php">
                            <div class="form-group">
                                <label for="salle">Salle</label>
                                <select class="form-control" id="salle" name="salle" required>
                                    <?php
                                    // Connexion à la base de données pour récupérer les types de salles disponibles
                                    $conn = new mysqli($host, $username, $password, $dbname);
                                    $result = $conn->query("SELECT * FROM salle");
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['num_salle'] . "'>" . $row['type_salle'] . "</option>";
                                    }
                                    $conn->close();
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="date">Date de réservation</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                            <div class="form-group">
                                <label for="heure">Heure de début</label>
                                <input type="time" class="form-control" id="heure" name="heure" required>
                            </div>
                            <div class="form-group">
                                <label for="duree">Durée (en minutes)</label>
                                <input type="number" class="form-control" id="duree" name="duree" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Affichage des réservations existantes -->
        <h2>Mes Réservations</h2>
        <?php if (count($reservations) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Heure de début</th>
                        <th>Durée</th>
                        <th>Type de Salle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reservation['num_reservation']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['date_reservation']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['heure_debut']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['duree']); ?> minutes</td>
                            <td><?php echo htmlspecialchars($reservation['type_salle']); ?></td>
                            <td>
                                <a href="edit_reservation.php?id=<?php echo htmlspecialchars($reservation['num_reservation']); ?>" class="btn btn-warning">Modifier</a>
                                <a href="delete_reservation.php?id=<?php echo htmlspecialchars($reservation['num_reservation']); ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune réservation trouvée.</p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
