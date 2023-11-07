<?php
require_once("src/loader/AutoLoader.php");

$autoloader = new \iutnc\deefy\loader\Autoloader('iutnc\deefy','src');
$autoloader -> register();


// CONNEXION A LA BASE DE DONNEE //
\iutnc\deefy\db\ConnectionFactory::setConfig("db.config.ini");
$bddPDO = \iutnc\deefy\db\ConnectionFactory::makeConnection();
    
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