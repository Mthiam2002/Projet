<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article</title>
</head>

<body>
    <h2>Publier un article</h2>
    <form action="publication_traitement.php" method="post" enctype="multipart/form-data">
        <label for="title">Titre de l'article :</label><br>
        <input type="text" id="title" name="title" required><br><br>

        <label for="content">Contenu de l'article :</label><br>
        <textarea id="content" name="content" rows="4" cols="50" required></textarea><br><br>

        <label for="image">Image (optionnel) :</label><br>
        <input type="file" id="image" name="image"><br><br>

        <input type="submit" value="Publier">
    </form>

    <h2>Évaluer un article</h2>
    <form action="evaluation_traitement.php" method="post">
        <label for="article_id">ID de l'article :</label><br>
        <input type="text" id="article_id" name="article_id" required><br><br>

        <label for="rating">Évaluation (de 1 à 5) :</label><br>
        <input type="number" id="rating" name="rating" min="1" max="5" required><br><br>

        <input type="submit" value="Évaluer">
    </form>
</body>

</html>

<?php
session_start();

// Vérifier si l'utilisateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: connexion.php");
    exit;
}

// Se connecter à la base de données (remplacez les valeurs par les vôtres)
$serveur = "localhost";
$utilisateur = "votre_nom_utilisateur";
$motdepasse = "votre_mot_de_passe";
$baseDeDonnees = "nom_de_votre_base_de_donnees";

$connexion = new mysqli($id, $user_id, $nom, $description, $content, $created_at, $image);

// Vérifier la connexion
if ($connexion->connect_error) {
    die("Connexion échouée : " . $connexion->connect_error);
}

// Récupérer les données du formulaire
$title = $_POST['title'];
$content = $_POST['content'];

// Vérifier s'il y a une image
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];

    // Déplacer l'image vers le dossier de destination
    $destination = "images/" . $image;
    move_uploaded_file($image_tmp, $destination);
} else {
    $image = null; // Aucune image fournie
}

// Insérer les données dans la base de données
$username = $_SESSION['username'];
$insertQuery = "INSERT INTO articles (title, content, image, username) VALUES ('$title', '$content', '$image', '$username')";

if ($connexion->query($insertQuery) === TRUE) {
    echo "Article publié avec succès !";
} else {
    echo "Erreur lors de la publication de l'article : " . $connexion->error;
}

$connexion->close();

session_start();

// Vérifier si l'utilisateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: connexion.php");
    exit;
}

// Se connecter à la base de données (remplacez les valeurs par les vôtres)
$serveur = "localhost";
$utilisateur = "votre_nom_utilisateur";
$motdepasse = "votre_mot_de_passe";
$baseDeDonnees = "nom_de_votre_base_de_donnees";

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $baseDeDonnees);

// Vérifier la connexion
if ($connexion->connect_error) {
    die("Connexion échouée : " . $connexion->connect_error);
}

// Récupérer les données du formulaire
$article_id = $_POST['article_id'];
$rating = $_POST['rating'];

// Vérifier si l'article existe dans la base de données
$checkArticleQuery = "SELECT * FROM articles WHERE id='$article_id'";
$result = $connexion->query($checkArticleQuery);

if ($result->num_rows > 0) {
    // Insérer l'évaluation dans la base de données
    $username = $_SESSION['username'];
    $insertRatingQuery = "INSERT INTO evaluations (article_id, username, rating) VALUES ('$article_id', '$username', '$rating')";

    if ($connexion->query($insertRatingQuery) === TRUE) {
        echo "Évaluation enregistrée avec succès pour l'article ID: " . $article_id;
    } else {
        echo "Erreur lors de l'enregistrement de l'évaluation : " . $connexion->error;
    }
} else {
    echo "L'article avec l'ID " . $article_id . " n'existe pas.";
}

$connexion->close();


?>