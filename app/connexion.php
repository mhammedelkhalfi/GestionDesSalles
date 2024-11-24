<?php
$host = '127.0.0.1';
$dbname = 'reservation_db'; // Le nom de votre base de données
$username = 'root'; // Votre nom d'utilisateur MySQL
$password = ''; // Votre mot de passe MySQL, si vide laissez-le comme cela

try {
    // Création de la connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Définir le mode d'erreur de PDO pour lancer des exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}


//$host = 'mysql-container';
//$dbname = 'reservation_db'; // Le nom de votre base de données
//$username = 'root'; // Votre nom d'utilisateur MySQL
//$password = 'root'; // Votre mot de passe MySQL, si vide laissez-le comme cela

//try {
    // Création de la connexion PDO
    //$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Create connection
    // Définir le mode d'erreur de PDO pour lancer des exceptions
    //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//} catch (PDOException $e) {
    //echo "Erreur de connexion à la base de données : " . $e->getMessage();
//}
?>
