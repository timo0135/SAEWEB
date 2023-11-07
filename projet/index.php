<?php

use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\loader\Autoloader;

require_once("src/loader/AutoLoader.php");

$autoloader = new Autoloader('iutnc\deefy','src');
$autoloader -> register();


// CONNEXION A LA BASE DE DONNEE //
ConnectionFactory::setConfig("db.config.ini");
$bddPDO = ConnectionFactory::makeConnection();
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOUITER</title>
</head>
<body>
    <div class="title">TOUITES</div><br>
    <br><br>

    <?php
// AFFICHAGE DES TOUITES //
    
    ?>
</body>
</html>