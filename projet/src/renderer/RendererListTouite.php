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
        if(!isset($_SESSION['id'])) {
            $this->listTouite = "select * from touite where answer is NULL order by date";
        }else{
            $this->listTouite="select * from touite where answer is NULL and id_touite not in(SELECT id_touite FROM touite inner join subsribe on subsribe.publisher=touite.id_user where subsriber=".$_SESSION['id']." and answer is NULL) order by date";
        }
        $this->resultSet = $bdd->prepare($this->listTouite);
        $this->resultSet->execute();
    }

    public function render(){
        $bdd=ConnectionFactory::makeConnection();
        $affichage="";
        if(isset($_SESSION['id'])) {
            $perso = new RendererTouiteSub();
            $affichage = $perso->render();
        }
        while ($row=$this->resultSet->fetch()){
            $user="select firstname, lastname from user where id_user=".$row['id_user'];
            $res=$bdd->prepare($user);
            $res->execute();
            $us=$res->fetch();
            $affichage.="<div class='touite'><h2 class='proprioTouite'>".$us['firstname']." ".$us['lastname']."</h2><p class='messageTouite'>".$row['message']."</p><br>";
            if(!is_null($row['path'])){
                $affichage=$affichage."<img class='imageTouite' src=".$row['path']." alt=".$row['description']."><br>";
            }
            $affichage=$affichage."<a href=/index.php?id=".$row['id_touite']." class='voirplus'>Voir plus</a></div><br>";
        }
        return $affichage;

    }

}