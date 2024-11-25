<?php
// Inclure la connexion à la base de données
include 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_salle = $_POST['type_salle'];

    if (!empty($type_salle)) {
        $sql = "INSERT INTO salle (type_salle) VALUES (:type_salle)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':type_salle', $type_salle);

        if ($stmt->execute()) {
            header('Location: gestion_salles.php?success=added');
        } else {
            echo "Erreur lors de l'ajout de la salle.";
        }
    } else {
        echo "Le type de salle est requis.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Salle</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Ajouter une Salle</h2>
        <form method="POST" action="add_salle.php">
            <div class="form-group">
                <label for="type_salle">Type de Salle</label>
                <input type="text" name="type_salle" id="type_salle" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
</body>
</html>
