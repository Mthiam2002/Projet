<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <form action="inscription_traitement.php" method="post">
        <label for="username">email: </label><br>
        <input type="email" id="username" name="username" required><br><br>

        <label for="password">Mot de passe :</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="age">Mot de passe :</label><br>
        <input type="default" id="age" name="age" required><br><br>

        <label for="sex">Homme </label><br>
        <input type="radioButton" id="sexe" name="sexe" required><br><br>
        <label for="sex">Femme </label><br>
        <input type="radioButton" id="sexe" name="sexe" required><br><br>

        <input type="submit" value="S'inscrire">
    </form>

    <form action="connexion_traitement.php" method="post">
        <label for="username">Nom d'utilisateur :</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Mot de passe :</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Se connecter">
    </form>

</body>

</html>

<?php
// Se connecter à la base de données (remplacez les valeurs par les vôtres)
$serveur = "localhost";
$utilisateur = "votre_nom_utilisateur";
$motdepasse = "votre_mot_de_passe";
$baseDeDonnees = "nom_de_votre_base_de_donnees";

$connexion = new mysqli($id, $email, $motdepasse, $age, $sex);

// Vérifier la connexion
if ($connexion->connect_error) {
    die("Connexion échouée : " . $connexion->connect_error);
}

// Récupérer les données du formulaire
$username = $_POST['username'];
$password = $_POST['password'];

// Hasher le mot de passe (pour des raisons de sécurité)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insérer les données dans la base de données
$insertQuery = "INSERT INTO utilisateurs (username, password) VALUES ('$username', '$hashedPassword')";

if ($connexion->query($insertQuery) === TRUE) {
    echo "Inscription réussie !";
} else {
    echo "Erreur lors de l'inscription : " . $connexion->error;
}

$connexion->close();

session_start();

// Se connecter à la base de données (remplacez les valeurs par les vôtres)
$serveur = "localhost";
$utilisateur = "votre_nom_utilisateur";
$motdepasse = "votre_mot_de_passe";
$baseDeDonnees = "nom_de_votre_base_de_donnees";

$connexion = new mysqli($id, $email, $motdepasse, $age, $sex);

// Vérifier la connexion
if ($connexion->connect_error) {
    die("Connexion échouée : " . $connexion->connect_error);
}

// Récupérer les données du formulaire
$username = $_POST['username'];
$password = $_POST['password'];

// Vérifier l'utilisateur dans la base de données
$selectQuery = "SELECT * FROM utilisateurs WHERE username='$username'";
$result = $connexion->query($selectQuery);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        // Authentification réussie, définir les sessions
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        echo "Connexion réussie ! Bienvenue, " . $_SESSION['username'];
    } else {
        echo "Mot de passe incorrect";
    }
} else {
    echo "Utilisateur non trouvé";
}

session_start();
// Détruire toutes les sessions
session_destroy();
echo "Vous avez été déconnecté avec succès";
?>