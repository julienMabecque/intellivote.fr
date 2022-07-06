<?php
require_once dirname(__FILE__).'/../config.php';


if (isset($_SESSION['id'])){
    if (!isset($_GET['revoke'])) {

        echo '<!DOCTYPE html>
        <html lang="fr">

        <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <meta http-equiv="Content-Security-Policy" content="default-src \'self\'; img-src https://* \'self\' data:; style-src https://* \'self\' \'unsafe-inline\' child-src \'none\';">

        <title>Intellivote - Espace Gouvernement - Révoquer un accès</title>

        <link href="css/custom.css" rel="stylesheet">

        <!-- Bootstrap core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="css/blog-home.css" rel="stylesheet">

        </head>

        <body>

        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-danger">
            <div class="container">
            <a class="navbar-brand" href="index.php"><img src="image/logo.png" width="160" height="30"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span id="new-dark-navbar-toggler-icon" class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="https://www.intellivote.fr">Espace électeur</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://mairie.intellivote.fr">Espace mairie</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="https://gouv.intellivote.fr">Espace Gouvernement<span class="sr-only">(current)</span></a>
                </li>';

                echo '
                </ul>
            </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container">

            <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">';

                echo '<h1 class="my-4">Espace Gouvernement - Révoquer un accès</h1>';

                $gouv_fetch = $bdd->prepare('SELECT * FROM governor WHERE individual = ? AND verified = 1;');
                $gouv_fetch->execute(array($_SESSION['id']));
                $gouv = $gouv_fetch->fetch();

                if (!$gouv) {
                  echo '<div class="alert alert-danger fade show" role="alert">
                    <strong>Vous n\'avez pas accès à l\'espace Gouvernement.</strong> Assurez-vous d\'avoir bien validé votre compte <a href="https://www.intellivote.fr/validation.php">en cliquant ici</a>. D\'ici là, par sécurité, vous devez utiliser l\'interface de gestion interne d\'Intellivote pour pouvoir administrer le service, la connexion à distance n\'est pas possible. Intellivote ne vous demandera jamais vos identifiants ni codes de vérifications, ne les communiquez jamais.
                  </div><br><br>';
                } else {

                        echo '
                        <h2><a>Révoquer un employé de mairie :</a></h2>
                        <form action="election.php?revoke=true" method="post">';

                        echo '

                            <div class="form-group">
                                <label for="mayorindv">Saisissez l\'ID de l\'employé de la mairie</label>
                                <input type="text" name="mayorindv" class="form-control';

                                if (isset($_GET['mayorindverror'])){
                                    echo ' is-invalid';
                                }

                                echo ' "id="mayorindv" placeholder="Saisissez l\'ID de l\'employé de mairie" required> ';

                                if (isset($_GET['descriptionerror'])){
                                    echo '<div class="invalid-feedback">
                                    ID de l\'employé de la mairie incorrect ! Vérifiez votre saisie.
                                    </div>';
                                }

                                echo ' <small id="NameHelp" class="form-text text-muted">
                                    Veuillez saisir l\'identifiant de l\'employé du maire. Celui-ci vous avait été communiqué lors de son enregistrement. En cas de problème, contactez un modérateur.
                                </small>

                                <label for="idmairie">Saisissez l\'ID de la mairie </label>
                                <div>
                                    <input type="text" name="idmairie" class="form-control';
                                    if (isset($_GET['idmairieerror'])){
                                        echo ' is-invalid';
                                    }
                                    echo '" id="idmairie" placeholder="Saisissez -1 pour révoquer tous les accès" required>
                                </div>';

                                if (isset($_GET['idmairieerror'])){
                                    echo '<div class="invalid-feedback">
                                    ID de la mairie incorrect ! L\'employé ne travavaille peut-être pas dans cette mairie.
                                    </div>';
                                }

                                echo '
                                <small id="DateHelp" class="form-text text-muted">
                                    Vous pouvez indiquer -1 pour révoquer l\'accès de l\'employé à toutes les mairies du pays.
                                </small>


                            </div>

                            <button type="submit" class="btn btn-danger">Révoquer les accès de l\'employé de la mairie</button>

                        </form><br><br>';


                      echo '
                      <a class = "btn btn-danger" href = "index.php">Annuler</a>
                      <br><br>';


                }

                echo '
                <a class = "btn btn-secondary" href = "logout.php">Se déconnecter</a>
                <br><br>';

            echo '</div>

            </div>
            <!-- /.row -->

        </div>
        <!-- /.container -->

        <!-- Footer -->
        <footer class="py-5" style="background-color: #e04a51;">
            <div class="container">
            <p class="m-0 text-center text-white">&copy; 2022 Intellivote. Tous droits reservés. <a href="https://www.intellivote.fr/legal.php" style="color: lightcyan;">Mentions légales</a>.</p>
            </div>
            <!-- /.container -->
        </footer>

        <!-- Bootstrap core JavaScript -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        </body>

        </html>';

    } else if (isset($_POST['name'])){

        // Pas d'inscription en double
        $req = $bdd->prepare('SELECT * FROM candidate WHERE party=? AND name=? AND surname=? AND election=?;');
        $req->execute(array($_POST['party'],$_POST['name'],$_POST['surname'],$_POST['election']));
        $test = $req->fetch();

        if ($test){
          header( "refresh:0;url=election.php?ajoutcandidat=true&candidaterror=true" );
        } else {

            $req=$bdd->prepare('INSERT INTO candidate (party, name, surname, programme, election, mairie) VALUES (:party, :name, :surname, :programme, :election, :mairie);');
            $req->execute(array(
                'party'=> $_POST['party'],
                'name'=> $_POST['name'],
                'surname'=> $_POST['surname'],
                'programme'=> $_POST['programme'],
                'election'=> $_POST['election'],
                'mairie'=> $_POST['idmairie']
            ));

            header( "refresh:0;url=election.php?ajoutcandidat=true&successcandidat=true" );
        }

    } else if (isset($_POST['cdelete'])) {
      $req = $bdd->prepare('SELECT * FROM election WHERE id = ?;');
      $req->execute(array($_POST['delete']));
      $test = $req->fetch();

      $date = date('Y-m-d H:i');

      if (($test["begindate"]>$date && date('Y-m-d H:i', strtotime($test['begindate'] . ' - 7 days'))>date('Y-m-d H:i')) || ($test["enddate"]<=$date && date('Y-m-d H:i', strtotime($row['enddate'] . ' + 7 days'))<=date('Y-m-d H:i'))) {
        $del1 = $bdd->prepare('DELETE FROM votes WHERE election = ?;');
        $del1->execute(array($_POST['delete']));

        $del2 = $bdd->prepare('DELETE FROM voted WHERE election = ?;');
        $del2->execute(array($_POST['delete']));

        $del3 = $bdd->prepare('DELETE FROM candidate WHERE election = ?;');
        $del3->execute(array($_POST['delete']));

        $del4 = $bdd->prepare('DELETE FROM election WHERE id = ?;');
        $del4->execute(array($_POST['delete']));

        header( "refresh:0;url=election.php?affiche=true&deletesuccess=true" );
      } else {
        header( "refresh:0;url=election.php?affiche=true&deleteerror=true" );
      }


    } else {

        // Pas de description/nom en double parmis ceux non finis
        $req = $bdd->prepare('SELECT * FROM election WHERE description = ?;');
        $req->execute(array($_POST['description']));
        $test = $req->fetch();

        if ($test){
          header( "refresh:0;url=election.php?ajout=true&descriptionerror=true" );
        }
        else if ($_POST['begindate']<date('Y-m-d H:i', strtotime(' + 90 days'))){ // Date de début qu'à partir de demain
          header( "refresh:0;url=election.php?ajout=true&beginerror=true" );
        }
        else if ($_POST['begindate']>=date('Y-m-d H:i', strtotime($_POST['enddate'].' + 8 hours'))){ // Date de fin qu'à partir de la date de début
            header( "refresh:0;url=election.php?ajout=true&enderror=true" );
        }
        else{

          $req=$bdd->prepare('INSERT INTO election (description, begindate, enddate, type) VALUES (:description, :begindate, :enddate, :type);');
          $req->execute(array(
            'description'=> $_POST['description'],
            'begindate'=> $_POST['begindate'],
            'enddate'=> $_POST['enddate'],
            'type'=> 1
          ));

          header( "refresh:0;url=index.php?successelection=true");

        }

    }

} else {
  header( "refresh:0;url=login.php?expired=true" );
}

?>