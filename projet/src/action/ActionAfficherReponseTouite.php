<?php

namespace iutnc\deefy\action;


use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\manip\ManipDislike;
use iutnc\deefy\manip\ManipLike;

class ActionAfficherReponseTouite extends Action{

    private $touite;
    private $resultSet;
    private $commentaire;
    private $resCom;

    public function execute() : string{
        $bdd=ConnectionFactory::makeConnection();

        // Requête SQL pour récupérer le touite principal
        $this->touite = "select * from touite where id_touite=?";
        $this->resultSet = $bdd->prepare($this->touite);
        $this->resultSet->bindParam(1, $_GET['id']);
        $this->resultSet->execute();
        $row=$this->resultSet->fetch();

        // Requête SQL pour récupérer les touites qui répondent au touite principal
        $this->commentaire="select * from touite where answer=? order by date";
        $this->resCom = $bdd->prepare($this->commentaire);
        $this->resCom->bindParam(1, $_GET['id']);
        $this->resCom->execute();

        // Requête SQL pour récupérer le nom et prénom de la personne qui a posté le touite principal
        $user="select firstname, lastname from user where id_user=?";
        $res=$bdd->prepare($user);
        $res->bindParam(1, $row['id_user']);
        $res->execute();
        $us=$res->fetch();

        // Ajoute le nom, prénom et le lien vers la page de cette utilisateur
        $affichage="<fieldset class='touite-box'>
            <legend>
                <a href='?action=page-user&iduser=".$row['id_user']."'><h2>".$us['firstname']." ".$us['lastname']."</h2></a>
            </legend>
            <p>";

        // Sépare tout les mots du message pour trouver les tags
        $tabPartieTouite=explode(" ",$row['message']);
        foreach ($tabPartieTouite as $t){
            // Si le mot commence par un #
            if(substr($t,0,1)==="#"){
                // On récupère son id
                $sql="SELECT id_tag from tag where label=?";
                $resultSet2=$bdd->prepare($sql);
                $resultSet2->bindParam(1,$t);
                $resultSet2->execute();
                $row2=$resultSet2->fetch();
                $id_tag=$row2['id_tag'];
                // On ajoute un lien vers la page du tag
                $affichage.="<a id='tag_touite' href='index.php?action=page-tag&id_tag=$id_tag' > $t</a>";
            }else{
                // Si ce n'est pas un tag on l'affiche normalement
                $affichage.=" $t";
            }
        }
        $affichage.="</p>";

        // On vérifie si il y a une image
        if(!is_null($row['path'])){
            // On affiche l'image
            $affichage.="<img src=".$row['path']." alt=".$row['description']."><br>";
        }
        // On affiche la date
        $affichage=$affichage."<p>Touite posté le: ".$row['date']."</p>";

        $id=$_GET['id'];
        $s1="SELECT count(*) AS 'nb' FROM `like` WHERE id_touite=:id_touite and `like`=1";
        $s2="SELECT count(*) AS 'nb' FROM `like` WHERE id_touite=:id_touite and `like`=0";
        $l1 = $bdd->prepare($s1);
        $l2 = $bdd->prepare($s2);
        $l1->bindParam(':id_touite', $id);
        $l2->bindParam(':id_touite', $id);
        $l1->execute();
        $l2->execute();
        $like=$l1->fetch();
        $dislike=$l2->fetch();

        $affichage .= "<p>&nbsp&nbsp&nbsp&nbsp{$like['nb']} : <a href=index.php?action=like&id=$id><button class='abb'>Like</button></a>&nbsp&nbsp&nbsp&nbsp{$dislike['nb']} : <a href=index.php?action=dislike&id=$id><button class='abb'>dislike</button></a></p></div><br>";


        $user="select role from user where id_user=?";
        $res=$bdd->prepare($user);
        $res->bindParam(1, $_SESSION['id']);
        $res->execute();
        $us=$res->fetch();
        $affichage.="<div class='bottom'>";
        $affichage.="<a href='index.php?action=publierTouite&id=$id' style='width:100%'><button class='repTouite'>Répondre à ce tweet&nbsp&nbsp<img src='icon/plus.png' style='width:30px;margin:0;'></button></a><br>";
        if(isset($_SESSION['id'])){
            if($_SESSION['id']==$row['id_user'] || $us['role']==100){
                $affichage .= "<a href=index.php?action=sup&id=$id><button class='btnSup'>Supprimer</button></a>";
            }
        }
        $affichage.="</div>";
        $affichage.= "</fieldset>";



        $affichage=$affichage."<h1 class='rep'>Réponse</h1><br>";
        while ($row=$this->resCom->fetch()){
            $user="select firstname, lastname from user where id_user=?";
            $res=$bdd->prepare($user);
            $res->bindParam(1, $row['id_user']);
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

