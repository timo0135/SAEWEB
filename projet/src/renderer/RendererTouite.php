<?php

namespace iutnc\deefy\renderer;


use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\touite\Touite;

class RendererTouite{

    private $touite;
    private $resultSet;
    private $commentaire;
    private $resCom;

    public function __construct(int $id){
        $bdd=ConnectionFactory::makeConnection();
        $this->touite = "select * from touite where id_touite=".$id;
        $this->resultSet = $bdd->prepare($this->touite);
        $this->resultSet->execute();
        $this->commentaire="select * from touite where answer=".$id;
        $this->resCom = $bdd->prepare($this->commentaire);
        $this->resCom->execute();
    }

    public function render(){
        $bdd=ConnectionFactory::makeConnection();
        $row=$this->resultSet->fetch();
        $user="select firstname, lastname from user where id_user=".$row['id_user'];
        $res=$bdd->prepare($user);
        $res->execute();
        $us=$res->fetch();


        $affichage="<div></div><h2>".$us['firsname']." ".$us['lastname']."</h2><br><p>".$row['message']."</p><br>";
        if(!is_null($row['path'])){
            $affichage=$affichage."<img src=".$row['path']."><br>";
        }
        $affichage=$affichage."<p>".$row['date']."</p><br><p>".$row['description']."</p>";



        while ($row=$this->resCom->fetch()){
            $user="select firstname, lastname from user where id_user=".$row['id_user'];
            $res=$bdd->prepare($user);
            $res->execute();
            $us=$res->fetch();
            $affichage="<div></div><h2>".$us['firsname']." ".$us['lastname']."</h2><br><p>".$row['message']."</p><br>";
            if(!is_null($row['path'])){
                $affichage=$affichage."<img src=".$row[path]."><br>";
            }
            $affichage=$affichage."<a href=/index.php?idT=".$row['id_touite'].">Voir plus</a></div><br>";
        }
        return $affichage;
    }

}