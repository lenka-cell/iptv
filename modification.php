<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles.css">
    <title>Résultats</title>
  </head>
  <body class="modif">



<?php
include('configsql.php');

if(isset($_POST['idmodif'])){
  $modifId = $_POST['idmodif'];
  $req = $bdd->query("SELECT * FROM video WHERE numero = '$modifId'");
	$donnees = $req->fetch();
  $nomVideo = $donnees['nomVideo'];
  $multicast = $donnees['multicast'];
  $description = $donnees['description'];
?>
  <form class="" action="modification.php" method="post">
    <input type="hidden" name="idmodif" value="<?php echo $modifId;?>">
    <p>Nom : </p>
    <input type="text" name="modifnomvideo" value="<?php echo $nomVideo;?>">
    <p>Description : </p>
    <textarea type="text" name="modifdescription" value="<?php echo $description;?>"></textarea>
    <p>Adresse multicast : </p>
    <select name="modifmulticast" class="">
        <option value="<?php echo $multicast;?>"><?php echo $multicast;?></option>
        <option value="239.1.1.1">239.1.1.1</option>
        <option value="239.1.1.2">239.1.1.2</option>
        <option value="239.1.1.3">239.1.1.3</option>
        <option value="239.1.1.4">239.1.1.4</option>
        <option value="239.1.1.5">239.1.1.5</option>
        <option value="239.1.1.6">239.1.1.6</option>
        <option value="239.1.1.7">239.1.1.7</option>
        <option value="239.1.1.8">239.1.1.8</option>
        <option value="239.1.1.9">239.1.1.9</option>
        <option value="239.1.1.10">239.1.1.10</option>
        <option value="239.1.1.11">239.1.1.11</option>
        <option value="239.1.1.12">239.1.1.12</option>
        <option value="239.1.1.13">239.1.1.13</option>
        <option value="239.1.1.14">239.1.1.14</option>
        <option value="239.1.1.15">239.1.1.15</option>
        <option value="239.1.1.16">239.1.1.16</option>
        <option value="239.1.1.17">239.1.1.17</option>
        <option value="239.1.1.18">239.1.1.18</option>
        <option value="239.1.1.19">239.1.1.19</option>
        <option value="239.1.1.20">239.1.1.20</option>
        <option value="239.1.1.21">239.1.1.21</option>
        <option value="239.1.1.22">239.1.1.22</option>
        <option value="239.1.1.23">239.1.1.23</option>
        <option value="239.1.1.24">239.1.1.24</option>
        <option value="239.1.1.25">239.1.1.25</option>
        <option value="239.1.1.26">239.1.1.26</option>
        <option value="239.1.1.27">239.1.1.27</option>
        <option value="239.1.1.28">239.1.1.28</option>
        <option value="239.1.1.29">239.1.1.29</option>
        <option value="239.1.1.30">239.1.1.30</option>
    </select></br>
    <button type="submit" name="sendmodif">Exécuter</button>
  </form>
  <?php

  if(isset($_POST['modifnomvideo']) && isset($_POST['modifmulticast']) && isset($_POST['modifdescription'])){
  $nomVideo = $_POST['modifnomvideo'];
  $multicast = $_POST['modifmulticast'];
  $description = $_POST['modifdescription'];
  $existe = $bdd->prepare("SELECT DISTINCT multicast FROM video WHERE multicast='$multicast'
  GROUP BY multicast ");
  $verifmulticast = "";
  $existe->execute();
  // 2- compte le nombre de ligne (max 1)
  $count = $existe->rowCount();
  if($count==0 AND $nomVideo !="" AND $description !="" AND $multicast !=""){
    $modif = $bdd->query("UPDATE video SET nomVideo = '$nomVideo', multicast='$multicast', description = '$description' WHERE numero = '$modifId'");
    echo "La vidéo à été modifiée avec succès.";
    echo "<br>";
    $verifmulticast = 1;
  ?>
    <a href="index.php">Cliquez ici pour continuer</a>
    </body>
  </html>
  <?php
}
elseif($count==1)
{
    echo '<br>Adresse Multicast déja utilisée.<br>';
    $verifmulticast = 0;
}
elseif($description =="")
{
    echo '<br>Veuillez saisir une description.<br>';
    $verifmulticast = 0;
}
elseif($nomVideo =="")
{
    echo '<br>Veuillez saisir un nom.<br>';
    $verifmulticast = 0;
}
}
}
?>
