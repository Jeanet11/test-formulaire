<?php session_start();
if(!isset($_SESSION['flash_message'])) {
  $_SESSION['flash_message'] = 'la variable n existe pas';
}

elseif (!empty($_SESSION['flash_message'])) {
  echo $_SESSION['flash_message'];
  $_SESSION['flash_message'] = '';
}  


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>Starter Template Bootstrap + Modal</title>

  <!-- Bootstrap -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

  <!-- My css file -->
  <link rel="stylesheet" type="text/css" href="css/style.css">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
  <div class="container">
    <h1>Ajout d'un utilisateur</h1>
    <?php
        //Fonction de test de champ vide
    function testEmptyField($field) {
      if(empty($_POST[$field])) {
        return "Le champ ".$field." est vide.<br>";
      }
    }



        //Si le formulaire est soumis
    if(isset($_POST['valider'])) {
          //Validation du formulaire
      $erreur = "";
      $message = "";

      if( strlen($_POST['pwd1']) < 8 ) {
        $erreur .= "Longueur du mot de passe doit être supérieur à 8<br>" ;
      }
      if ($_POST['pwd1'] != $_POST['pwd2']) {
        $erreur .= "Les mots de passes ne sont pas identiques<br>";
      }
      $erreur .= testEmptyField('prenom');
      $erreur .= testEmptyField('nom');
      $erreur .= testEmptyField('age');
      $erreur .= testEmptyField('pseudo');
      $erreur .= testEmptyField('email');

          //S'il n'y a pas d'erreur
      if( empty($erreur)) {
            //////////////////////////////////////////////////
            //Traitement: On insère l'utilisateur en base
            //////////////////////////////////////////////////
            //1-Connexion à la base de données et requete
        try
        {
              // On se connecte à MySQL
          $bdd = new PDO('mysql:host=localhost;dbname=annonces-immo-dump;charset=utf8', 'root', 'admin');
        }
        catch(Exception $e)
        {
              // En cas d'erreur, on affiche un message et on arrête tout
          die('Erreur : '.$e->getMessage());
        }

            //2-Préparation des valeurs à insérer ( sécurisation )
        $prenom = htmlspecialchars($_POST['prenom']);
        $nom = htmlspecialchars($_POST['nom']);
        $age = htmlspecialchars($_POST['age']);
        $email = htmlspecialchars($_POST['email']);
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $password = password_hash($_POST['pwd1'], PASSWORD_DEFAULT);

            //3-Préparation de la requete sql
        $sql = sprintf("INSERT INTO uti_utilisateur(uti_prenom, uti_nom, uti_age, uti_email, uti_pseudo, uti_password) VALUES ('%s', '%s', %d, '%s', '%s', '%s');", $prenom, $nom, $age, $email, $pseudo, $password);

            //4-Exécution et vérification
        if( $bdd->exec($sql) == 1 ) {
          $message = "L'utilisateur a bien été inséré!";
          $_SESSION['flash_message'] = $message;

          header("Location: ajout_utilisateur.php");
        } else {
          $erreur .= "Impossible d'insérer ces données: " . $bdd->errorInfo()[2];
        }
      }

          //On affiche les informations d'erreur ou de succès
      if( !empty($erreur) ) {
        echo "<p class='text-danger'>" . $erreur . "</p>";
      } else {
        echo "<p class='text-success'>" . $message . "</p>";
      }
    }
    ?>


    <form class="form-horizontal" method='post' action='ajout_utilisateur.php'>
      <div class="form-group">
        <label class="control-label col-md-3" for="prenom">Prénom:</label>
        <div class="col-md-9">
          <input type="text" class="form-control" id="prenom" name="prenom" value="<?= isset($_POST['prenom']) ? $_POST['prenom'] : "" ?>" required >
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-3" for="nom">Nom:</label>
        <div class="col-md-9">
          <input type="text" class="form-control" id="nom" name="nom" value="<?= isset($_POST['nom']) ? $_POST['nom'] : "" ?>" required>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-3" for="age">Age:</label>
        <div class="col-md-9">
          <input type="number" min="1" max="99" class="form-control" id="age" name="age" value="<?= isset($_POST['age']) ? $_POST['age'] : "" ?>" required>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-3" for="pseudo">Pseudo:</label>
        <div class="col-md-9">
          <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?= isset($_POST['pseudo']) ? $_POST['pseudo'] : "" ?>" required>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-3" for="pwd1">Password:</label>
        <div class="col-md-9">
          <input type="password" class="form-control" id="pwd1" name="pwd1" value="<?= isset($_POST['pwd1']) ? $_POST['pwd1'] : "" ?>" required>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-3" for="pwd2">Confirmation password:</label>
        <div class="col-md-9">
          <input type="password" class="form-control" id="pwd2" name="pwd2"
          value="<?= isset($_POST['pwd2']) ? $_POST['pwd2'] : "" ?>" required>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-3" for="email">Email:</label>
        <div class="col-md-9">
          <input type="email" class="form-control" id="email" name="email" value="<?= isset($_POST['email']) ? $_POST['email'] : "" ?>" required pattern="^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-z]{2,4}$">
        </div>
      </div>
      <div class="col-md-offset-3 col-md-9">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <input type="submit" class="btn btn-primary" data-dismiss="modal" name="valider" value="Valider"/>
      </div>
    </form>

  </div><!-- End container -->

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>

