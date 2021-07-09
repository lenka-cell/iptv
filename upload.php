<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles.css">
    <title>Résultats</title>
  </head>
  <body class="upload">


<?php
include('configsql.php');

if(empty($_POST['multicast'])){
  echo "Veuillez renseigner une adresse";
  ?>
  <br>
  <a href="index.php">Cliquez ici pour revenir en arrière</a>
  <?php
  exit();
}
$multicastTemp = $_POST['multicast'];
$multicast = "239.1.1." . $multicastTemp;

if(empty($_POST['description'])){
  echo "Veuillez renseigner une description";
  ?>
  <br>
  <a href="index.php">Cliquez ici pour revenir en arrière</a>
  <?php
  exit();
}
$description = $_POST['description'];


// *** Partie relative à la vidéo ***
//  **Envoi de la vidéo sur le serveur**
$target_dir_video = "/var/www/iptv/uploads/videos/";
if(empty($_FILES["video"]["name"])){
  echo "Veuillez choisir une vidéo";
  ?>
  <br>
  <a href="index.php">Cliquez ici pour revenir en arrière</a>
  <?php
  exit();
}
$name_video = basename($_FILES["video"]["name"]);
$target_file_video = $target_dir_video . $name_video;
$uploadOk_video = 1;
$videoFileType = strtolower(pathinfo($target_file_video,PATHINFO_EXTENSION));

// Vérifie si la vidéo existe déjà
if (file_exists($target_file_video)) {
  echo "La vidéo existe déjà.";
  $uploadOk_video = 0;
}

// Autorise certains formats
if($videoFileType != "mp4" && $videoFileType != "mkv") {
  echo "Pas le bon format de vidéo.";
  $uploadOk_video = 0;
}

// Vérifie si une erreur est arrivée
if ($uploadOk_video == 0) {
  echo "Une erreur est survenue, la vidéo n'a pas été envoyée<br>";
// Si tout est bon, essaye d'envoyer la vidéo
} else {
  if (move_uploaded_file($_FILES["video"]["tmp_name"], $target_file_video)) {
    echo "La video ". htmlspecialchars( basename( $_FILES["video"]["name"])). " a été envoyée avec succès.<br>";
  } else {
    echo "Une erreur est survenue lors de l'envoie de la vidéo.<br>";
  }
}


// *** Partie relative à l'image' ***
//  **Envoi de l'image sur le serveur**
$target_dir_image = "/var/www/iptv/uploads/logos/";
if(empty($_FILES["image"]["name"])){
  echo "Veuillez choisir une image";
  ?>
  <br>
  <a href="index.php">Cliquez ici pour revenir en arrière</a>
  <?php
  exit();
}
$name_image = basename($_FILES["image"]["name"]);
$target_file_image = $target_dir_image . $name_image;
$uploadOk_image = 1;
$imageFileType = strtolower(pathinfo($target_file_image,PATHINFO_EXTENSION));

// Vérifie si l'image existe déjà
if (file_exists($target_file_image)) {
  echo "L'image existe déjà.";
  $uploadOk_image = 0;
}

// Autorise certains formats
if($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
  echo "Pas le bon format d'image.";
  $uploadOk_image = 0;
}

// Vérifie si une erreur est arrivée
if ($uploadOk_image == 0) {
  echo "Une erreur est survenue, l'image n'a pas été envoyée.<br>";
// Si tout est bon, essaye d'envoyer la vidéo
} else {
  if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file_image)) {
    echo "L'image' ". htmlspecialchars( basename( $_FILES["image"]["name"])). " a été envoyée avec succès.<br>";
  } else {
    echo "Une erreur est survenue lors de l'envoie de l'image.<br>";
  }
}

//  **Envoie des informations de la vidéo sur la base de donnée**
//Verifie si l'adresse multicast est déjà utilisée
$existe = $bdd->prepare("SELECT DISTINCT multicast FROM video WHERE multicast='$multicast'
GROUP BY multicast ");
$verifmulticast = "";
$existe->execute();
// 2- compte le nombre de ligne (max 1)
$count = $existe->rowCount();
// si pas de ligne contenant l'adresse multicast et le  nom du film et de l'affiche différent de vide
if($count==0 AND $target_file_video !="" AND $target_file_image !="")
    {
    $id = NULL;
    $req = $bdd->prepare('INSERT INTO video(nomVideo, description, cheminLogo, cheminVideo, multicast, chaine)
    VALUES(:nomVideo, :description, :cheminLogo, :cheminVideo, :multicast, :chaine)');
    $req->execute(array(
	     'nomVideo' => $name_video,
	     'description' => $description,
	     'cheminLogo' => $target_file_image,
	     'cheminVideo' => $target_file_video,
	     'multicast' => $multicast,
       'chaine' => $multicastTemp
	));
    $req->CloseCursor();
    echo "<br>Adresse Multicast ajoutée.<br>";
    $addressused = $bdd->query("UPDATE adresse SET utilisee = 1 WHERE suffixeId = '$multicastTemp'");
    $verifmulticast = 1;
    }
    elseif($count==1)
    {
        echo '<br>Adresse Multicast déja utilisée<br>';
        $verifmulticast = 0;
    }
?>
<br>
<a href="index.php">Cliquez ici pour continuer</a>
</body>
</html>
