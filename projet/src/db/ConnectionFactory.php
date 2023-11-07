<?php

namespace iutnc\deefy\db;

class ConnectionFactory{

    public static $info = array();


    public static function setConfig($file){
        ConnectionFactory::$info = parse_ini_file($file);
    }

    public static function makeConnection(){
        return new \PDO("mysql:host=".ConnectionFactory::$info['host'].";dbname=".ConnectionFactory::$info['name'], ConnectionFactory::$info['user'], ConnectionFactory::$info['mdp']);
    }

}

