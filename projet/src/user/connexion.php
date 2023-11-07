<?php
namespace iutnc\deefy\user;

session_start();
if(isset($_SESSION['id'])){
    // L'utilisateur est déjà connecté //
}
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    // Le formulaire n'a pas été remplit //
    header('location:inscription-form.php');
    exit();
}
if(empty($_POST['email']) || empty($_POST['mot_de_passe'])){
    // Des informations sont manquantes //
}

$commande = "
SELECT id FROM User WHERE 
email = '".filter_var ( $_POST['email'],FILTER_SANITIZE_EMAIL)."' AND 
password = '".hash('sha256',filter_var($_POST['mot_de_passe'],FILTER_SANITIZE_STRING))."'";

$res = $PDO -> query($commande);

if($row = $res -> fetch()){
    // Les informations sont bonnes //
    $_SESSION['id'] = $row['id'];
}else{
    // Les informations sont incorrectes //
}
?>