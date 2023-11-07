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
<div class='err'>Des informations sont incorrectes</div>
            ";
            break;
    }
}
?>

    <div class="title">TOUITER</div><br>
    <br><br>
    <div class="form-fit">
        <div class="sub-title">SE CONNECTER</div>
        <form action="connexion.php" method="POST">
            <p>EMAIL</p>
            <input type="email" name='email'><br>
            <p>MOT DE PASSE</p>
            <input type="password" name='mot_de_passe'><br><br>
            <button>se connecter</button>
        </form>
    </div>
</body>
</html>