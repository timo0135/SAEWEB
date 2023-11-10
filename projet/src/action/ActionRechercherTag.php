<?php

namespace iutnc\touiter\action;

use iutnc\touiter\db\ConnectionFactory;

class ActionRechercherTag extends Action
{

    public function execute(): string
    {
        $res="";
        //On selectionne toute les informations d'une tag qui ressemble au mit entré par l'utilisateur
        $sql="SELECT id_tag,label,description from TAG where label like ?";
        $bdd=ConnectionFactory::makeConnection();
        $resultSet=$bdd->prepare($sql);
        $str = $_POST['recherche'] . "%";
        $resultSet->bindParam(1, $str);
        $resultSet->execute();
        //on afficher chaque tag retourner par la requête
        while ($row=$resultSet->fetch()){
            $id_tag=$row['id_tag'];
            //on regarde si l'utilisateur est abonné au tag que l'on va afficher
            $sql2="select * from USER2TAG where id_tag=? and id_user=?";

            $resultSet2=$bdd->prepare($sql2);
            $resultSet2->bindParam(1,$row['id_tag']);
            $resultSet2->bindParam(2,$_SESSION['id']);
            $resultSet2->execute();
            $abonnement="S abonner";
            //si il est deja abonné au tag le bouton va afficher Se désabonner au lieu de s'abonner
            if($resultSet2->fetch()){
                $abonnement="Se désabonner";
            }
            //on met un lien sur le nom du tag qui envoie vers la page du tag et on affiche le tag
            $res.="<div class =tag>
                <a href='index.php?action=page-tag&id_tag=".$row['id_tag']."'><h4 class='labeltag'>".$row['label']."</h4></a><br>";
            //si il y a une description on l'affiche
            if(!is_null($row['description'])){
                $res.="<p>".$row['description']."</p><br>";
            }else{
                //sinon pas de description
                $res.="<p>Pas de description</p><br>";
            }
            //bouton pour s'abonner ou se desabonner d'un tag
            $res.="<form  method='post' action='index.php?action=subscribeTag&id_tag=$id_tag'>
                <input class='abb' type='submit' name='subscribeTag' value='$abonnement'><br>
                </form>";
            $res.="</div>";
        }
        return $res;
    }
}