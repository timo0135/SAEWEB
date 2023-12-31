<?php
require_once("src/db/ConnectionFactory.php");

use iutnc\backoffice\db\ConnectionFactory;

error_reporting(E_ALL);
ini_set("display_errors", 1);


session_start();
if(!isset($_SESSION['ida'])){
    // L'utilisateur est déjà connecté //
    header('location:connexion.php');
    exit();
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
    <div class='title'><h1>BACK-OFFICE</h1></div>
    <br>
    <div class='container'>
        <fieldset class='box-fit'>
            <legend><h2>BEST TAG</h2></legend>
<?php

// CONNEXION A LA BASE DE DONNEE //
ConnectionFactory::setConfig("db.config.ini");
$bddPDO = ConnectionFactory::makeConnection();

// On cherche la page des tags à afficher //
if(isset($_GET['pagetag'])){
    $pagetag=(int) $_GET['pagetag'];
}else{
    $pagetag= 1;
}

// On cherche la page des user à afficher //
if(isset($_GET['pageuser'])){
    $pageuser=(int) $_GET['pageuser'];
}else{
    $pageuser= 1;
}

// Affichage des meilleurs tags //

$commande="SELECT TAG.id_tag AS id,TAG.label AS lb,count(*) AS nb FROM TAG JOIN TOUITE2TAG ON TOUITE2TAG.id_tag = TAG.id_tag GROUP BY TAG.id_tag ORDER BY count(*) DESC LIMIT 10 OFFSET ".(($pagetag-1)*10).";";
$i=1;
$result=$bddPDO->query($commande);
while($row = $result->fetch()){
    echo "<a class='lst-tag' href='../projet/index.php?action=page-tag&id_tag=".$row['id']."'>".($i + 10*($pagetag-1))."- ".$row['lb']."&nbsp(".$row['nb'].")</a><br>";
    $i++;
}
echo "<br><div class='container'>";

// Si on est pas à la première page //
if($pagetag != 1){
    // On affiche la possibilité d'aller à la page précedente //
    echo "
        <a href='index.php?pageuser=".$pageuser."&pagetag=".($pagetag-1)."'>precedent</a>
    ";
}

// Si on est pas à la dernière page //
if($i == 11){
    // On affiche la possibilité d'aller à la page suivante //
    echo "
        <a href='index.php?pageuser=".$pageuser."&pagetag=".($pagetag+1)."'>suivant</a>
    ";
}
echo "</div>";
?>
        </fieldset>
        <fieldset class='box-fit'>
            <legend><h2>BEST PUBLISHER</h2></legend>
            <br>
            <?php

// CONNEXION A LA BASE DE DONNEE //
ConnectionFactory::setConfig("db.config.ini");
$bddPDO = ConnectionFactory::makeConnection();

// Affichage des meilleurs User //

$commande="SELECT id_user,email,count(*) AS nb FROM USER JOIN SUBSRIBE ON USER.id_user = SUBSRIBE.publisher GROUP BY email ORDER BY count(*) DESC LIMIT 10 OFFSET ".(($pageuser-1)*10).";";
$i=1;
$result=$bddPDO->query($commande);
while($row = $result->fetch()){
    echo "<a class='lst-tag' href='../projet/index.php?action=page-user&id_user=".$row['id_user']."'>".($i + 10*($pageuser-1))."- ".$row['email']."&nbsp(".$row['nb'].")</a><br>";
    $i++;
}
echo "<br><div class='container'>";

// Si on est pas à la première page //
if($pageuser != 1){
    // On affiche la possibilité d'aller à la page précedente //
    echo "
        <a href='index.php?pageuser=".($pageuser-1)."&pagetag=".$pagetag."'>precedent</a>
    ";
}

// Si on est pas à la dernière page //
if($i == 11){
    // On affiche la possibilité d'aller à la page suivante //
    echo "
        <a href='index.php?pageuser=".($pageuser+1)."&pagetag=".$pagetag."'>precedent</a>
    ";
}
echo "</div>
<a href='connexion.php?d=1' style='width:fit-content;position:absolute;bottom:5px;right:5px;'><img src='icon/deco.png' style='width:30px;margin:0;'></a><br>";
?>
        </div>
    </div>
</body>
</html>