<?php
namespace iutnc\deefy\user;
require_once("../db/ConnectionFactory.php");


session_start();
if(isset($_SESSION['id'])){
    // L'utilisateur est déjà connecté //
    // Erreur n°1 de index.php//
    header('location:../../index.php?err=1');
    exit();
}
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    // Le formulaire n'a pas été remplit //
    // Erreur n°1 de connexion-form.php//
    header('location:connexion-form.php?err=1');
    exit();
}
if(empty($_POST['email']) || empty($_POST['mot_de_passe'])){
    // Des informations sont manquantes //
    // Erreur n°2 de connexion-form.php//
    header('location:connexion-form.php?err=2');
    exit();
}

// CONNEXION A LA BASE DE DONNEE //
\iutnc\deefy\db\ConnectionFactory::setConfig("../../db.config.ini");
$bddPDO = \iutnc\deefy\db\ConnectionFactory::makeConnection();


$commande = "
SELECT id_user,password FROM User WHERE 
email = '".filter_var ($_POST['email'],FILTER_SANITIZE_EMAIL)."'";

$res = $bddPDO -> query($commande);

if($row = $res -> fetch()){
    if(!password_verify(filter_var ($_POST['mot_de_passe'],FILTER_SANITIZE_SPECIAL_CHARS),$row['password'])){
        // Des informations sont incorrectes //
        // Erreur n°3 de connexion-form.php//
        header('location:connexion-form.php?err=3');
        exit();
    }
    // Les informations sont bonnes //
    // Succes n°2 de index.php//
    $_SESSION['id'] = $row['id_user'];
    header("location:../../index.php?succ=2");
    exit();
}else{
    // Des informations sont incorrectes //
    // Erreur n°3 de connexion-form.php//
    header('location:connexion-form.php?err=3');
    exit();
}
?>