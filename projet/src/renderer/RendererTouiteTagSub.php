<?php

namespace iutnc\deefy\renderer;

use iutnc\deefy\db\ConnectionFactory;

class RendererTouiteTagSub
{
    private $listTouite;
    private $resultSet;

    public function __construct(){
        $bdd=ConnectionFactory::makeConnection();
        $this->listTouite = "select distinct touite.* from TOUITE inner join TOUITE2TAG on touite.id_touite = TOUITE2TAG.id_touite inner join USER2TAG on TOUITE2TAG.id_tag = USER2TAG.id_tag where USER2TAG.id_user =".$_SESSION['id']." order by touite.date desc";
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
            $affichage=$affichage."<fieldset class='touite-box'><legend><a href='?action=page-user&iduser=".$row['id_user']."'><h2 class='proprioTouite'>".$us['firstname']." ".$us['lastname']."</h2></a></legend><p class='messageTouite'>".$row['message']."</p><br>";
            if(!is_null($row['path'])){
                $affichage=$affichage."<img class='imageTouite' src=".$row['path']." alt=".$row['description']."><br>";
            }
            $affichage=$affichage."<a  href=index.php?action=voirPlus&id=".$row['id_touite']." class='voirplus'>Voir plus</a></fieldset><br>";

        }
        return $affichage;
    }
}