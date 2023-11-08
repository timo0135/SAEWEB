<?php

namespace iutnc\deefy\renderer;


use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\manip\ActionDislike;
use iutnc\deefy\manip\ActionLike;
use iutnc\deefy\manip\ManipDislike;
use iutnc\deefy\manip\ManipLike;

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


        $affichage="<div class='touite'><h2 class='proprioTouite'>".$us['firstname']." ".$us['lastname']."</h2><br><p class='messageTouite'>".$row['message']."</p><br>";
        if(!is_null($row['path'])){
            $affichage=$affichage."<img src=".$row['path']." alt=".$row['description']."><br>";
        }
        $affichage=$affichage."<p class='dateTouite'>".$row['date']."</p><br><p>";
        while ($row=$this->resTag->fetch()){
            $affichage=$affichage.$row['libelle']." ";
        }
        $like=new ManipLike();
        $dislike=new ManipDislike();
        $affichage=$affichage."</p><br><div class='noteTouite'><input id='like' type='button' value='Like' onClick='".$like->execute()."'> <input id='dislike' type='button' value='Dislike' onClick='".$dislike->execute()."'></div></div><br>";



        $affichage=$affichage."<h1 class='rep'>Réponse</h1><br>";
        while ($row=$this->resCom->fetch()){
            $user="select firstname, lastname from user where id_user=".$row['id_user'];
            $res=$bdd->prepare($user);
            $res->execute();
            $us=$res->fetch();
            $affichage.="<div class='touite'><h2 class='proprioTouite'>".$us['firsname']." ".$us['lastname']."</h2><br><p class='messageTouite'>".$row['message']."</p><br>";
            if(!is_null($row['path'])){
                $affichage=$affichage."<img src=".$row['path']." alt=".$row['description']."><br>";
            }

            $affichage=$affichage."</div>";

        }
        return $affichage;
    }

}