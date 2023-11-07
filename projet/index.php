<?php

use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\loader\Autoloader;

require_once("src/loader/AutoLoader.php");

$autoloader = new Autoloader('iutnc\deefy','src');
$autoloader -> register();

