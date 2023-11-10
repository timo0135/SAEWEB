<?php
require_once("src/auth/Auth.php");
require_once("src/db/ConnectionFactory.php");

error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
if(isset($_SESSION['ida'])){
    header('location:index.php');
    exit();
}
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['email']) && isset($_POST['mdp'])){
        iutnc\backoffice\auth\Auth::authenticate($_POST['email'],$_POST['mdp']);
    }else{
        header('location:connexion.php');
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='style.css' rel='stylesheet'>
    <title>Back Office</title>
</head>
<body>
    <div class='titre'>CONNEXION</div>
    <form action="connexion.php" method="POST">
        Email<input type="text" name="email"><br>
        Mot de passe<input type="password" name="mdp"><br><br>
        <button>Se connecter</button>
    </form>
</body>
</html>