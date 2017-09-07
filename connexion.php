  <?php
  if(isset($_POST)) {
    if(!empty($_POST['pseudo']) && !empty($_POST['pwd'])) {
        //Récupération et sécurisation des parametres
      $pseudo = htmlspecialchars($_POST['pseudo']);
      $password = $_POST['pwd'];
        //Vérification mot de passe
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
        //Recherche utilisateur
      $sql = sprintf("select * from uti_utilisateur where uti_pseudo = '%s';", $pseudo);
      $response = $bdd->query($sql);
      $row = $response->fetch();
      if(password_verify($password, $row['uti_password'])) {
        session_start();
        $_SESSION['uti_pseudo'] = $pseudo;
        $_SESSION['uti_oid'] = $row['uti_oid'];
        header('Location: ajout_utilisateur.php');
      } else {
        echo "Identifiants incorrects";
      }
    }
  }
  ?>



  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Connexion</title>

    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
  </head>

  <body>
    

    <form class="form-horizontal" method='POST' >
      <div class="form-group">
        <label class="control-label col-md-3" for="pseudo">Pseudo</label>
        <div class="col-md-9">
          <input type="text" class="form-control" id="pseudo" name="pseudo"  required >
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-3" for="pwd">Password</label>
        <div class="col-md-9">
          <input type="password" class="form-control" id="pwd" name="pwd">
        </div>
      </div>
      <div>
        <input type="submit" class="btn btn-primary" data-dismiss="modal" name="connexion" value="connexion"/>
      </div>

      <div id="user"></div>

    </form>


  </body>
  </html>