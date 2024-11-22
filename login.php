<?php
// Inclure la connexion à la base de données
include 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérification de la validité des données
    if (!empty($email) && !empty($password)) {
        // Préparer la requête pour vérifier l'utilisateur
        $sql = "SELECT * FROM employe WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $employe = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'employé existe et si le mot de passe est correct
        if ($employe && password_verify($password, $employe['password'])) {
            // Connexion réussie, démarrer la session et stocker les informations
            session_start();
            $_SESSION['employe_id'] = $employe['num_employe'];
            $_SESSION['role'] = $employe['role'];
            $_SESSION['nom'] = $employe['nom'];
            $_SESSION['prenom'] = $employe['prenom'];
            $_SESSION['departement'] = $employe['departement'];
            // Redirection vers le tableau de bord
            header('Location: dashboard.php');
            exit();
        } else {
            echo "Email ou mot de passe incorrect.";
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
    <title>Connexion</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Connexion</h2>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
        </form>
        <div class="text-center mt-3">
            <p>Pas encore inscrit ? <a href="register.php">S'inscrire</a></p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
