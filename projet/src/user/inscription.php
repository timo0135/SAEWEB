<?php
namespace iutnc\deefy\user;

session_start();
if(isset($_SESSION['id'])){
    // L'utilisateur est déjà connecté //
    // Erreur n°1 //
    header('location:../index.php?err=1');
    exit();
}

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    // Le formulaire n'a pas été remplit //
    header('location:inscription-form.php');
    exit();
}

if(!empty($_POST['email']) || !empty($_POST['mot_de_passe']) || !empty($_POST['mot_de_passe_conf']) || !empty($_POST['nom']) || !empty($_POST['prenom']) ){
    // Des informations sont manquantes //
    header("location:inscription-form.php?err=")
    exit();
}

if($_POST['mot_de_passe']!=$_POST['mot_de_passe_conf']){
    // Les mots de passes ne sont pas les mêmes //
}

// Connexion à la base de donnée //
$bddPDO = \iutnc\deefy\db\ConnectionFactory::makeConnection();

$commande = "SELECT * FROM User WHERE email = '".filter_var ( $_POST['email'],FILTER_SANITIZE_EMAIL)."'";
$res = $PDO -> query($commande);
if($row = $res -> fetch()){
    // L'email est déjà utilisé //
}


// Insertion des données du nouvel utilisateur dans la base de donnée //
$commande = "
INSERT INTO User(email,password,firstname,lastname) 
VALUES ('".filter_var ( $_POST['email'],FILTER_SANITIZE_EMAIL)."','"
.hash('sha256',filter_var($_POST['mot_de_passe'],FILTER_SANITIZE_STRING))."','".
filter_var($_POST['nom'],FILTER_SANITIZE_SPECIAL_CHARS)."','".
filter_var($_POST['prenom'],FILTER_SANITIZE_SPECIAL_CHARS)."')";

try{
    $PDO -> query($commande);
    // Succes de l'insertion //
}catch{
    // Erreur d'insertion //
}

?>