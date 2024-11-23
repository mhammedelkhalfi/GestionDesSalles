<?php
// Inclure la connexion à la base de données
include 'connexion.php'; // Assurez-vous que le chemin vers db.php est correct

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $departement = $_POST['departement'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Vérification de la validité des données
    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($password) && !empty($role)) {
        // Hash du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Préparer la requête d'insertion
        $sql = "INSERT INTO employe (nom, prenom, email, departement, password, role) 
                VALUES (:nom, :prenom, :email, :departement, :password, :role)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':departement', $departement);
        $stmt->bindParam(':password', $hashedPassword); // Utilisation de la variable $hashedPassword
        $stmt->bindParam(':role', $role);

        // Exécuter la requête
        if ($stmt->execute()) {
            // Redirection vers la page de connexion après succès
            header('Location: login.php');
            exit();  // Arrêter l'exécution après la redirection
        } else {
            echo "Erreur lors de l'enregistrement de l'employé.";
        }
    } else {
        echo "Tous les champs sont requis.";
    }
}
?>

<!-- Formulaire HTML avec Bootstrap -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Inscription</h2>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" name="prenom" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="departement">Département:</label>
                <input type="text" id="departement" name="departement" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="role">Rôle:</label>
                <select name="role" id="role" class="form-control">
                    <option value="ADMIN">Admin</option>
                    <option value="USER">Utilisateur</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
        </form>
        <div class="text-center mt-3">
            <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
