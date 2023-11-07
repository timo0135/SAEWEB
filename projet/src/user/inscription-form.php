<?php
namespace iutnc\deefy\user;

session_start();
if(isset($_SESSION['id'])){
    // L'utilisateur est déjà connecté //
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
if(isset($_GET['err'])){
    switch($_GET['err']){
        case '1':
            echo "
<div class='err'>Le formulaire n'a pas été remplit</div>
            ";
            break;
        case '2':
            echo "
<div class='err'>Des informations sont manquantes</div>
            ";
            break;
        case '3':
            echo "
<div class='err'>Les mots de passes ne sont pas les mêmes</div>
            ";
            break;
        case '4':
            echo "
<div class='err'>L'email est déjà utilisé</div>
            ";
            break;
        case '5':
            echo "
<div class='err'>Erreur d'insertion</div>
            ";
            break;
    }
}elseif(isset($_GET['succ'])){
    switch($_GET['succ']){
    }
}
?>
    <div class="title">TWEETER</div><br>
    <br><br>
    <div class="form-fit">
        <div class="sub-title">S'INSCRIRE</div>
        <form action="inscription.php" method="POST">
            <p>EMAIL</p>
            <input type="email" name='email'><br>
            <p>NOM</p>
            <input type="text" name="nom"><br>
            <p>PRENOM</p>
            <input type="text" name='prenom'><br>
            <p>MOT DE PASSE</p>
            <input type="password" name="mot_de_passe"><br>
            <p>CONFIRMATION MOT DE PASSE</p>
            <input type="password" name="mot_de_passe_conf"><br><br>
            <button> s'inscrire</button>
        </form>
    </div>
</body>
</html>