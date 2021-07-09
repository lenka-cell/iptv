<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Authentification</title>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body class="auth">
    <?php
    $_SESSION['ident'] = TRUE;
    if($_SESSION['ident'] = FALSE){
      echo "Identifiants Incorrects";
      echo "</br>";
    }
     ?>
    <form class="" action="index.php" method="post">
      <h1>Authentification</h1>
      <input type="text" name="uname" placeholder="Utilisateur" required>
      <input type="password" placeholder="Mot de passe" name="psw" required>
      <button type="submit" name="submit">Connexion</button>
    </form>
  </body>
</html>
