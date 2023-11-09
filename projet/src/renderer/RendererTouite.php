<?php

namespace iutnc\deefy\renderer;


use iutnc\deefy\db\ConnectionFactory;
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


        $affichage="<fieldset class='touite-box'><legend><h2>".$us['firstname']." ".$us['lastname']."</h2></legend><p>";

        $message=explode(" ",$row['message']);
        foreach ($message as $t){
            if(substr($t,0,1)==='#') {
                $sql="SELECT id_tag from tag where label=?";
                $resultSet=$bdd->prepare($sql);
                $resultSet->bindParam(1,$t);
                $resultSet->execute();
                $row2=$resultSet->fetch();
                $affichage.="<a href=index.php?action=page-tag&id_tag=".$row2['id_tag']."> $t</a>";
            }else{
                $affichage.=" $t";
            }
        }
        $affichage.="</p><br>";
            //.$row['message']."</p><br>";
        if(!is_null($row['path'])){
            $affichage.="<img src=".$row['path']." alt=".$row['description']."><br>";
        }
        $affichage=$affichage."<p>".$row['date']."</p><br><p>";
        while ($row=$this->resTag->fetch()){
            $affichage.=$row['label']." ";
        }

        $id=$_GET['id'];
        $affichage .= "</p><br><p> <a href=index.php?action=like&id=$id>Like</a> <a href=index.php?action=dislike&id=$id>Dislike</a> </p></div><br>";
        $affichage.= "</fieldset>";

        $affichage=$affichage."<h1 class='rep'>Réponse</h1><br>";
        while ($row=$this->resCom->fetch()){
            $user="select firstname, lastname from user where id_user=".$row['id_user'];
            $res=$bdd->prepare($user);
            $res->execute();
            $us=$res->fetch();
            $affichage.="<fieldset class='touite-box'><legend><a href='?action=page-user&iduser=".$row['id_user']."'><h2>".$us['firstname']." ".$us['lastname']."</h2></a></legend><p>".$row['message']."</p><br>";
            if(!is_null($row['path'])){
                $affichage.="<img src=".$row['path']." alt=".$row['description']."><br>";
            }
            $affichage.="<a href=index.php?action=voirPlus&id=".$row['id_touite']." class='voirplus'>Voir plus</a></fieldset><br>";
        }
        return $affichage;
    }

}
?>

