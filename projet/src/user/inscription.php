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
    // Erreur n°1 de inscription-form.php//
    header('location:inscription-form.php?err=1');
    exit();
}

if(empty($_POST['email']) || empty($_POST['mot_de_passe']) || empty($_POST['mot_de_passe_conf']) || empty($_POST['nom']) || empty($_POST['prenom']) ){
    // Des informations sont manquantes //
    // Erreur n°2 de inscription-form.php//
    header("location:inscription-form.php?err=2");
    exit();
}

if($_POST['mot_de_passe']!=$_POST['mot_de_passe_conf']){
    // Les mots de passes ne sont pas les mêmes //
    // Erreur n°3 de inscription-form.php//
    header("location:inscription-form.php?err=3");
    exit();
}

// CONNEXION A LA BASE DE DONNEE //
\iutnc\deefy\db\ConnectionFactory::setConfig("../../db.config.ini");
$bddPDO = \iutnc\deefy\db\ConnectionFactory::makeConnection();

$commande = "SELECT * FROM user WHERE email = '".filter_var ( $_POST['email'],FILTER_SANITIZE_EMAIL)."'";
$res = $bddPDO -> query($commande);
if($row = $res -> fetch()){
    // L'email est déjà utilisé //
    // Erreur n°4 de inscription-form.php//
    header("location:inscription-form.php?err=4");
    exit();
}


// Insertion des données du nouvel utilisateur dans la base de donnée //
$commande = "
INSERT INTO User(email,password,firstname,lastname,role) 
VALUES ('".filter_var ( $_POST['email'],FILTER_SANITIZE_EMAIL)."','"
.password_hash(filter_var ($_POST['mot_de_passe'],FILTER_SANITIZE_SPECIAL_CHARS),PASSWORD_DEFAULT)."','".
filter_var($_POST['nom'],FILTER_SANITIZE_SPECIAL_CHARS)."','".
filter_var($_POST['prenom'],FILTER_SANITIZE_SPECIAL_CHARS)."',1)";

try{
    $bddPDO -> query($commande);
    // Succes de l'inscription //
    // Succes n°1 de index.php//
    header("location:../../index.php?succ=1");
    exit();
}catch (\Exception $e)  {
    // Erreur d'insertion //
    // Erreur n°5 de inscription-form.php//
    header("location:inscription-form.php?err=5");
    exit();
}

?>