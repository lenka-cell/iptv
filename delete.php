
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles.css">
    <title>Résultats</title>
  </head>
  <body class="delete">
    <?php
include('configsql.php');

if(isset($_POST['idsup']) && isset($_POST['videosup']) && isset($_POST['imagesup']) && isset($_POST['chainesup'])){
  $supId = $_POST['idsup'];
  $supVideo = $_POST['videosup'];
  $supImage = $_POST['imagesup'];
  $chainesup = $_POST['chainesup'];
  $delused = $bdd->query("UPDATE adresse SET utilisee = 0 WHERE suffixeId = '$chainesup'");
  $del = $bdd->query("DELETE FROM video WHERE numero = '$supId'");

  if(file_exists($supVideo)){
    unlink($supVideo);
  }
  if(file_exists($supImage)){
    unlink($supImage);
  }
  echo "La vidéo à bien été supprimée";

}
?>
</br>
    <a href="index.php">Cliquez ici pour continuer</a>
  </body>
</html>
