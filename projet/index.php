<?php

use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\dispatch\Dispatcher;
use iutnc\deefy\loader\Autoloader;

require_once("src/loader/AutoLoader.php");

$autoloader = new Autoloader('iutnc\deefy','src');
$autoloader -> register();

ConnectionFactory::setConfig('db.config.ini');


if(isset($_GET['action'])){
    $disp->action = $_GET['action'];
    $disp->run();
}
