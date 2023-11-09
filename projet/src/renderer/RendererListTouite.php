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
            $this->listTouite = "select * from touite order by date desc";
        }else{
            $this->listTouite="select * from touite where id_touite not in(SELECT id_touite FROM touite inner join subsribe on subsribe.publisher=touite.id_user where subsriber=".$_SESSION['id'].") and id_touite not in(SELECT touite.id_touite FROM `touite` INNER JOIN touite2tag on touite2tag.id_touite=touite.id_touite INNER JOIN user2tag on user2tag.id_tag=touite2tag.id_tag where user2tag.id_user=".$_SESSION['id'].") order by date desc";
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
            $sql="SELECT * FROM user2tag WHERE id_user=".$_SESSION['id'];
            $res=$bdd->prepare($sql);
            $res->execute();
            while ($row=$res->fetch()){
                $tag=new RendererTagTouite($row['id_user']);
                $affichage.=$tag->render();
            }
        }
        while ($row=$this->resultSet->fetch()){
            $user="select firstname, lastname from user where id_user=".$row['id_user'];
            $res=$bdd->prepare($user);
            $res->execute();
            $us=$res->fetch();
            $affichage.=
            "<div class='touite-box'>
                <h2>&nbsp&nbsp".$us['firstname']." ".$us['lastname']."</h2><p>".$row['message']."</p><br>";
            if(!is_null($row["answer"])){
                $commande="SELECT firstname FROM User Join Touite ON User.id_user = Touite.id_user WHERE Touite.id_user = ".$row["answer"];
                $res = $bdd->query($commande);
                $row2 = $res->fetch();
                $affichage.= "<div class='reply'>".$row[""];
            }
            if(!is_null($row['path'])){
                $affichage=$affichage."<img src=".$row['path']." alt=".$row['description']."><br>";
            }

            $affichage=$affichage."<a href=index.php?action=voirPlus&id=".$row['id_touite']." class='voirplus'>Voir plus</a></div>
            <br>";

        }
        return $affichage;

    }

}