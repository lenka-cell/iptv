<?php
try {
  $bdd = new PDO('mysql:host=localhost;dbname=iptv;charset=utf8', "AdminIPTV", "Adm&nSql2021");
  $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\Exception $e) {
  die('Erreur : ' . $e->getMessage());
  }

?>
