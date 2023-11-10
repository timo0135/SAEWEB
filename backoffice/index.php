<?php
require_once("src/db/ConnectionFactory.php");

use iutnc\backoffice\db\ConnectionFactory;

error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();
if(!isset($_SESSION['ida'])){
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


if(isset($_GET['pagetag'])){
    $pagetag=(int) $_GET['pagetag'];
}else{
    $pagetag= 1;
}

if(isset($_GET['pageuser'])){
    $pageuser=(int) $_GET['pageuser'];
}else{
    $pageuser= 1;
}



$commande="SELECT tag.id_tag AS id,tag.label AS lb,count(*) AS nb FROM tag JOIN touite2tag ON touite2tag.id_tag = tag.id_tag GROUP BY tag.id_tag ORDER BY count(*) DESC LIMIT 10 OFFSET ".(($pagetag-1)*10).";";
$i=1;
$result=$bddPDO->query($commande);
while($row = $result->fetch()){
    echo "<a class='lst-tag' href='../projet/index.php?action=page-tag&id_tag=".$row['id']."'>".($i + 10*($pagetag-1))."- ".$row['lb']."&nbsp(".$row['nb'].")</a><br>";
    $i++;
}
echo "<br><div class='container'>";
if($pagetag != 1){
    echo "
        <a href='index.php?pageuser=".$pageuser."&pagetag=".($pagetag-1)."'>precedent</a>
    ";
}
if($i == 11){
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


$commande="SELECT id_user,email,count(*) AS nb FROM User Join subsribe ON User.id_user = subsribe.publisher GROUP BY email ORDER BY count(*) DESC LIMIT 10 OFFSET ".(($pageuser-1)*10).";";
$i=1;
$result=$bddPDO->query($commande);
while($row = $result->fetch()){
    echo "<a class='lst-tag' href='../projet/index.php?action=page-user&id_user=".$row['id_user']."'>".($i + 10*($pageuser-1))."- ".$row['email']."&nbsp(".$row['nb'].")</a><br>";
    $i++;
}
echo "<br><div class='container'>";
if($pageuser != 1){
    echo "
        <a href='index.php?pageuser=".($pageuser-1)."&pagetag=".$pagetag."'>precedent</a>
    ";
}
if($i == 11){
    echo "
        <a href='index.php?pageuser=".($pageuser+1)."&pagetag=".$pagetag."'>precedent</a>
    ";
}
echo "</div>";
?>
        </div>
    </div>
</body>
</html>