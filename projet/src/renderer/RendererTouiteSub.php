<?php

namespace iutnc\deefy\renderer;

use iutnc\deefy\db\ConnectionFactory;

class RendererTouiteSub
{
    private $listTouite;
    private $resultSet;

    public function __construct(){
        session_start();
        $bdd=ConnectionFactory::makeConnection();
        $this->listTouite = "SELECT * FROM touite inner join subsribe on subsribe.publisher=touite.id_user where subsriber=".S_SESSION['id']." and answer is NULL order by date";
        $this->resultSet = $bdd->prepare($this->listTouite);
        $this->resultSet->execute();
    }

    public function render(){
        $bdd=ConnectionFactory::makeConnection();
        $affichage="";
        while ($row=$this->resultSet->fetch()){
            $user="select firstname, lastname from user where id_user=".$row['id_user'];
            $res=$bdd->prepare($user);
            $res->execute();
            $us=$res->fetch();
            $affichage=$affichage."<div><h2>".$us['firstname']." ".$us['lastname']."</h2><br><p>".$row['message']."</p><br>";
            if(!is_null($row['path'])){
                $affichage=$affichage."<img src=".$row['path']." alt=".$row['description']."><br>";
            }
            $affichage=$affichage."<a href=/index.php?id=".$row['id_touite'].">Voir plus</a></div><br>";
        }
        return $affichage;
    }

}