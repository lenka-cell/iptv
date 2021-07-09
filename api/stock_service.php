<?php
try {
  $bdd = new PDO('mysql:host=localhost;dbname=iptv;charset=utf8', 'AdminIPTV', 'Adm&nSql2021');
  $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\Exception $e) {
  die('Erreur : ' . $e->getMessage());
}
function requeteSql($id, $bdd){
	$req = $bdd->query("SELECT * FROM video WHERE numero = '$id'");
	$donnees = $req->fetch();
  $numero = $donnees['numero'];
  $cheminVideo = $donnees['cheminVideo'];
  $cheminImage = $donnees['cheminLogo'];
  $contenu = array();
  array_push($contenu, array("ID" => $donnees["numero"], "nomVideo" => $donnees["nomVideo"], "cheminLogo" => $donnees["cheminLogo"], "cheminVideo" => $donnees["cheminVideo"], "multicast" => $donnees["multicast"]));
  $resultatsJson = json_encode($contenu);
  echo $resultatsJson;

	?>

	<?php

}
?>
	<main>

<?php

$i = 1;
$sql = "SELECT numero FROM video ORDER BY numero ASC";

foreach  ($bdd->query($sql) as $row) {
    if($i == 1)
    {
    	$sectionClass= "first";
    }
    else
    {
    	$sectionClass= "next";
    }
    requeteSql($row[0], $bdd, $sectionClass);
    $i++;
}

$i=1;
?>
