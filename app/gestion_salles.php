<?php
session_start();

// Vérifier si l'utilisateur est connecté et administrateur
if (!isset($_SESSION['employe_id']) || $_SESSION['role'] !== 'ADMIN') {
    header('Location: login.php');
    exit();
}

// Inclure la connexion à la base de données
include 'connexion.php';

// Récupérer la liste des salles
$sql = "SELECT * FROM salle";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$salles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Salles</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Gestion des Réservations</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="reservations.php">Réservations</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php">Mon Profil</a></li>
                <?php if ($_SESSION['role'] === 'ADMIN'): ?>
                    <li class="nav-item"><a class="nav-link" href="gestion_salles.php">Gestion des Salles</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="logout.php">Se déconnecter</a></li>
            </ul>
        </div>
    </nav>

    <!-- Gestion des Salles -->
    <div class="container mt-5">
        <h2>Gestion des Salles</h2>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addModal">Ajouter une Salle</button>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type de Salle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($salles as $salle): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($salle['num_salle']); ?></td>
                        <td><?php echo htmlspecialchars($salle['type_salle']); ?></td>
                        <td>
                            <button class="btn btn-warning edit-btn" data-id="<?php echo $salle['num_salle']; ?>" data-type="<?php echo htmlspecialchars($salle['type_salle']); ?>" data-toggle="modal" data-target="#editModal">Modifier</button>
                            <a href="delete_salle.php?id=<?php echo $salle['num_salle']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette salle ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Ajouter une Salle -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="add_salle.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Ajouter une Salle</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="add_type_salle">Type de Salle</label>
                            <input type="text" name="type_salle" id="add_type_salle" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Modifier une Salle -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="edit_salle.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Modifier une Salle</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Champ caché pour l'ID -->
                        <input type="hidden" name="num_salle" id="edit_num_salle">
                        <div class="form-group">
                            <label for="edit_type_salle">Type de Salle</label>
                            <input type="text" name="type_salle" id="edit_type_salle" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Modifier</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Remplir le modal de modification -->
    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const type = button.getAttribute('data-type');

                document.getElementById('edit_num_salle').value = id;
                document.getElementById('edit_type_salle').value = type;
            });
        });
    </script>
</body>
</html>
