<?php

namespace iutnc\deefy\renderer;


use iutnc\deefy\action\ActionDislike;
use iutnc\deefy\action\ActionLike;
use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\touite\Touite;

class RendererTouite{

    private $touite;
    private $resultSet;
    private $commentaire;
    private $resCom;
    private $tag;
    private $resTag;

    public function __construct(int $id){
        $bdd=ConnectionFactory::makeConnection();
        $this->touite = "select * from touite where id_touite=".$id;
        $this->resultSet = $bdd->prepare($this->touite);
        $this->resultSet->execute();

        $this->commentaire="select * from touite where answer=".$id." order by date";
        $this->resCom = $bdd->prepare($this->commentaire);
        $this->resCom->execute();

        $this->tag="select * from tag inner join touite2tag on touite2tag.id_tag=tag.id_tag WHERE touite2tag.id_touite=".$id;
        $this->resTag=$bdd->prepare($this->tag);
        $this->resTag->execute();
    }

    public function render(){
        $bdd=ConnectionFactory::makeConnection();
        $row=$this->resultSet->fetch();
        $user="select firstname, lastname from user where id_user=".$row['id_user'];
        $res=$bdd->prepare($user);
        $res->execute();
        $us=$res->fetch();


        $affichage="<div><h2>".$us['firsname']." ".$us['lastname']."</h2><br><p>".$row['message']."</p><br>";
        if(!is_null($row['path'])){
            $affichage=$affichage."<img src=".$row['path']." alt=".$row['description']."><br>";
        }
        $affichage=$affichage."<p>".$row['date']."</p><br><p>";
        while ($row=$this->resTag->fetch()){
            $affichage=$affichage.$row['libelle']." ";
        }
        $like=new ActionLike();
        $dislike=new ActionDislike();
        $affichage=$affichage."</p><br><p><input type='button' value='Like' onClick='".$like->execute()."'> <input type='button' value='Dislike' onClick='".$dislike->execute()."'><br>";



        $affichage=$affichage."<h1>Réponse</h1><br>";
        while ($row=$this->resCom->fetch()){
            $user="select firstname, lastname from user where id_user=".$row['id_user'];
            $res=$bdd->prepare($user);
            $res->execute();
            $us=$res->fetch();
            $affichage="<div></div><h2>".$us['firsname']." ".$us['lastname']."</h2><br><p>".$row['message']."</p><br>";
            if(!is_null($row['path'])){
                $affichage=$affichage."<img src=".$row['path']." alt=".$row['description']."><br>";
            }
            $affichage=$affichage."<a href=/index.php?id=".$row['id_touite'].">Voir plus</a></div><br>";
        }
        return $affichage;
    }

}