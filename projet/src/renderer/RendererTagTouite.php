<?php

namespace iutnc\deefy\renderer;

use iutnc\deefy\db\ConnectionFactory;

class RendererTagTouite
{
    private $listTouite;
    private $resultSet;

    public function __construct($id){
        $bdd=ConnectionFactory::makeConnection();
        $this->listTouite = "select * from touite INNER JOIN touite2tag on touite2tag.id_touite=touite.id_touite where id_tag=".$id." and answer is NULL order by date";
        $this->resultSet = $bdd->prepare($this->listTouite);
        $this->resultSet->execute();
    }

    public function render(){
        $affichage="";
        $bdd=ConnectionFactory::makeConnection();
        while ($row=$this->resultSet->fetch()){
            $user="select firstname, lastname from user where id_user=".$row['id_user'];
            $res=$bdd->prepare($user);
            $res->execute();
            $us=$res->fetch();
            $affichage=$affichage."<fieldset class='touite-box'><legend><a href='?action=page-user&iduser=".$row['id_user']."'><h2 class='proprioTouite'>".$us['firstname']." ".$us['lastname']."</h2></a></legend><p class='messageTouite'>".$row['message']."</p><br>";
            if(!is_null($row['path'])){
                $affichage=$affichage."<img class='imageTouite' src=".$row['path']." alt=".$row['description']."><br>";
            }
            $affichage=$affichage."<a  href=index.php?action=voirPlus&id=".$row['id_touite']." class='voirplus'>Voir plus</a></fieldset>><br>";

        }
        return $affichage;
    }
}