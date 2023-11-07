<?php

namespace iutnc\deefy\renderer;

use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\render\Renderer;
use iutnc\deefy\touite\Touite;

class RendererListTouite{

    private $listTouite;
    private $resultSet;

    public function __construct(){
        $bdd=ConnectionFactory::makeConnection();
        $this->listTouite = "select * from touite where answer is NULL order by date";
        $this->resultSet = $bdd->prepare($this->listTouite);
        $this->resultSet->execute();
    }

    public function render(){
        $bdd=ConnectionFactory::makeConnection();
        while ($row=$this->resultSet->fetch()){
            $user="select firstname, lastname from user where id_user=".$row['id_user'];
            $res=$bdd->prepare($user);
            $res->execute();
            $us=$res->fetch();
            $affichage="<div></div><h2>".$us['firsname']." ".$us['lastname']."</h2><br><p>".$row['message']."</p><br>";
            if(!is_null($row['path'])){
                $affichage=$affichage."<img src=".$row['path']."><br>";
            }
            $affichage=$affichage."<a href=/index.php?idT=".$row['id_touite'].">Voir plus</a></div><br>";
        }
        return $affichage;
    }

}