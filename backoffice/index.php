<?php
require_once("src/db/ConnectionFactory.php");

use iutnc\backoffice\db\ConnectionFactory;

error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();
if(!isset($_SESSION['id'])){
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
        <div class='box-fit'>
            <div class='title'> BEST TAG</div>
<?php

// CONNEXION A LA BASE DE DONNEE //
ConnectionFactory::setConfig("db.config.ini");
$bddPDO = ConnectionFactory::makeConnection();


$commande="SELECT tag.id_tag AS id,tag.label AS lb,count(*) AS nb FROM tag JOIN touite2tag ON touite2tag.id_tag = tag.id_tag GROUP BY tag.id_tag ORDER BY count(*) DESC";
$i=1;
$result=$bddPDO->query($commande);
while($row = $result->fetch()){
    echo "<a class='lst-tag' href='../projet/index.php?action=page-tag&id_tag=".$row['id']."'>$i- ".$row['lb']."&nbsp(".$row['nb'].")</a><br>";
    $i++;
}
?>
        </div>
        <div class='box-fit'>
            <div class='title'> BEST PUBLISHER</div><br>
            <br>
            <?php

// CONNEXION A LA BASE DE DONNEE //
ConnectionFactory::setConfig("db.config.ini");
$bddPDO = ConnectionFactory::makeConnection();


$commande="SELECT id_user,email,count(*) AS nb FROM User Join subsribe ON User.id_user = subsribe.publisher GROUP BY email ORDER BY count(*) DESC";
$i=1;
$result=$bddPDO->query($commande);
while($row = $result->fetch()){
    echo "<a class='lst-tag' href='../projet/index.php?action=page-user&id_user=".$row['id_user']."'>$i- ".$row['email']."&nbsp(".$row['nb'].")</a><br>";
    $i++;
}
?>
        </div>
    </div>
</body>
</html>