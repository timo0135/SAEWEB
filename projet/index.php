<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

use iutnc\touiter\db\ConnectionFactory;
use iutnc\touiter\dispatch\Dispatcher;
use iutnc\touiter\loader\Autoloader;

require_once("src/loader/AutoLoader.php");
session_start();

$autoloader = new Autoloader('iutnc\touiter','src');
$autoloader -> register();

ConnectionFactory::setConfig('db.config.ini');



if(isset($_GET['action'])){
    $disp = new Dispatcher($_GET['action']);
}else{
    $disp = new Dispatcher("choisir");
}
$disp->run();
