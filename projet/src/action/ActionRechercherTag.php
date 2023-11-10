<?php

namespace iutnc\touiter\action;

use iutnc\touiter\db\ConnectionFactory;

class ActionRechercherTag extends Action
{

    public function execute(): string
    {
        $res="";
        $sql="SELECT id_tag,label,description from tag where label like ?";
        $bdd=ConnectionFactory::makeConnection();
        $resultSet=$bdd->prepare($sql);
        $str = $_POST['recherche'] . "%";
        $resultSet->bindParam(1, $str);
        $resultSet->execute();
        while ($row=$resultSet->fetch()){
            $id_tag=$row['id_tag'];
            $sql2="select * from user2tag where id_tag=? and id_user=?";

            $resultSet2=$bdd->prepare($sql2);
            $resultSet2->bindParam(1,$row['id_tag']);
            $resultSet2->bindParam(2,$_SESSION['id']);
            $resultSet2->execute();
            $abonnement="S abonner";
            if($resultSet2->fetch()){
                $abonnement="Se désabonner";
            }
            $res.="<div class =tag>
                <a href='index.php?action=page-tag&id_tag=".$row['id_tag']."'><h4 class='labeltag'>".$row['label']."</h4></a><br>";
            if(!is_null($row['description'])){
                $res.="<p>".$row['description']."</p><br>";
            }else{
                $res.="<p>Pas de description</p><br>";
            }
            $res.="<form  method='post' action='index.php?action=subscribeTag&id_tag=$id_tag'>
                <input class='abb' type='submit' name='subscribeTag' value='$abonnement'><br>
                </form>";
            $res.="</div>";
        }
        return $res;
    }
}