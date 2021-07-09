<?php
session_start();
include('configsql.php');
 ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="styles.css">
  <title>IPTV</title>
</head>
<body>
  <aside>
    <h1>Ajout de vidéos</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data" class="ajoutVideos">
      <p>Veuillez selectionner une vidéo à envoyer : </p>
      <input type="file" name="video" id="video" class="inputfile">
      <label for="video">Choisir une vidéo</label>
      <p>Veuillez selectionner une image à envoyer : </p>
      <input type="file" name="image" id="image" class="inputfile">
      <label for="image">Choisir une image</label>
      <p>Veuillez saisir une description : </p>
      <textarea type="text" name="description" class="description"></textarea>
      <p>Veuillez selectionner une chaine : </p>
      <select onchange="fetch_select(this.value);" name="multicast">
        <option value="">Chaines</option>
        <?php
        $select = $bdd->query("SELECT suffixeId FROM adresse  WHERE utilisee = 0 GROUP BY suffixeId");
        while($row = $select->fetch())
        {
          echo "<option>".$row['suffixeId']."</option>";
        }
         ?>

      </select>

      <button type="submit" name="submit">Envoyer</button>
    </form>
    <hr>
    <article>
      <h3>Stopper toutes les vidéos</h3>
      <form class="" action="index.php" method="post">
        <button class="killvlc" type="submit" name="killall">Stopper</button>
      </form>
    </article>
  </aside>
  <main>
    <h1>Liste des vidéos</h1>

<?php



function requeteSql($id, $bdd){
  $req = $bdd->query("SELECT * FROM video WHERE numero = '$id'");
  $donnees = $req->fetch();
  $numero = $donnees['numero'];
  $cheminVideo = $donnees['cheminVideo'];
  $cheminImage = $donnees['cheminLogo'];
  $imageHtml = substr($cheminImage, 13);
  $chaine = $donnees['chaine'];
?>

    <section class="globale">
      <img src="<?php echo $imageHtml; ?>" alt="">
      <div class="infos">
        <p>Nom de la vidéo : <?php echo $donnees['nomVideo']; ?></p>
        <p>Description : <?php echo $donnees['description']; ?></p>
        <p>Adresse multicast : <?php echo $donnees['multicast']; ?></p>
      </div>
      <div class="formulaires">
      <form class="listevideos" action="modification.php" method="post">
        <input type="hidden" name="idmodif" value="<?php echo $numero;?>">
        <button type="submit" name="modifier">Modifier</button>
      </form>
      <form class="listevideos" action="delete.php" method="post">
        <input type="hidden" name="idsup" value="<?php echo $numero;?>">
        <input type="hidden" name="videosup" value="<?php echo $cheminVideo;?>">
        <input type="hidden" name="imagesup" value="<?php echo $cheminImage;?>">
        <input type="hidden" name="chainesup" value="<?php echo $chaine;?>">
        <button type="submit" name="supprimer">Supprimer</button>
      </form>
      </div>
      <div class="multicast">
      <form class="listevideos" action="index.php" method="post">
        <input type="hidden" name="idchoixmulticast" value="<?php echo $numero;?>"></br>
        <input type="hidden" name="chainechoixmulticast" value="<?php echo $chaine;?>"></br>
        <p>Diffuser
        <input type="radio" name="choixmulticast" value="diffuser"></p>
        <?php
        $check = $bdd->query("SELECT difusee FROM adresse WHERE id = '$chaine'");
        $donneesCheck = $check->fetch();
        $isDiffusionOk = $donneesCheck['difusee'];
        if($isDiffusionOk == 0){
        echo"<span style='color: red'>&#10003;</span>";
        }
        else {
          echo"<span style='color: green'>&#10003;</span>";
        }
         ?>
      </br>
        <p>Stopper
        <input type="radio" name="choixmulticast" value="stopper"></p></br>
        <button type="submit" name="valeurmulticast">Exécuter</button>
      </form>
    </div>
    </section>
<?php
}
$i = 1;
$sql = "SELECT numero FROM video ORDER BY chaine ASC";

foreach  ($bdd->query($sql) as $row) {
  requeteSql($row[0], $bdd);
  $i++;
}
$i=1;

if(isset($_POST['choixmulticast']) && isset($_POST['idchoixmulticast']) && isset($_POST['chainechoixmulticast'])) {
  $choixmulticast = $_POST['choixmulticast'];
  $idMulticast = $_POST['idchoixmulticast'];
  $req = $bdd->query("SELECT * FROM video WHERE numero = '$idMulticast'");
  $donnees = $req->fetch();
  $path_Film = $donnees['cheminVideo'];
  $vlc = $path_Film;
  $multicast = $donnees['multicast'];
  $chaine = $_POST['chainechoixmulticast'];
  $broadcast= "cvlc $vlc --sout '#transcode{vcodec=h264,acodec=mpga,ab=128,channels=2,samplerate=44100}:udp{dst=$multicast:1234}' --ttl 12 --loop ";
  if($choixmulticast == "diffuser"){
  exec("$broadcast >/dev/null 2>/dev/null &");
  $check = $bdd->query("UPDATE adresse SET difusee = 1 WHERE id = '$chaine'");
  }
  if($choixmulticast == "stopper"){
    $output = NULL;
    $retveal = NULL;
    exec("ps -aux |grep $multicast: |cut -c9-17", $output, $retveal);
    $resultatPs = $output[0];
    exec("kill $resultatPs");
    $check = $bdd->query("UPDATE adresse SET difusee = 0 WHERE id = '$chaine'");
  }
}

if(isset($_POST['killall'])){
  exec("killall vlc");
  $check = $bdd->query("UPDATE adresse SET difusee = 0");
}
?>
  </main>
</body>
<script type="text/javascript">
  function fetch_select(val){
    $.ajax({
      type : 'post',
      url : 'fetch_data.php',
      data : {
        get_option:val
      },
      success : function (response){
        document.getElementById("select_multicast").innerHTML=response;
      }
    });
  }
</script>
</html>
