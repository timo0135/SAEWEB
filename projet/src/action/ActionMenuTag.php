<?php

namespace iutnc\touiter\action;

use iutnc\touiter\db\ConnectionFactory;

class ActionMenuTag extends Action
{

    public function execute(): string
    {
        $res="<h1 class='rep'>Tag: </h1>";
        $bdo=ConnectionFactory::makeConnection();
        $sql="SELECT tag.id_tag as idTag, label,description from tag inner join user2tag on tag.id_tag=user2tag.id_tag where id_user=?";
        $resultSet=$bdo->prepare($sql);
        $resultSet->bindParam(1,$_SESSION['id']);
        $resultSet->execute();
        if ($row = $resultSet->fetch()) {
            do {
                $sql2="select * from user2tag where id_tag=? and id_user=?";

                $resultSet2=$bdo->prepare($sql2);
                $resultSet2->bindParam(1,$row['idTag']);
                $resultSet2->bindParam(2,$_SESSION['id']);
                $resultSet2->execute();
                $abonnement="S abonner";
                if($resultSet2->fetch()){
                    $abonnement="Se désabonner";
                }
                $res.="<div class =tag>
                <a href='index.php?action=page-tag&id_tag=".$row['idTag']."'><h4 class='labeltag'>".$row['label']."</h4></a><br>";
                if(!is_null($row['description'])){
                    $res.="<p>".$row['description']."</p><br>";
                }else{
                    $res.="<p>Pas de description</p><br>";
                }
                $res.="<form  method='post' action='index.php?action=subscribeTag&id_tag=".$row['idTag']."'>
                <input class='abb' type='submit' name='subscribeTag' value='$abonnement'><br>
                </form>";
                $res.="</div>";
            } while ($row = $resultSet->fetch());
        } else {
            $res.="
                <p class='aucunTag'>Vous êtes abonnés à aucun tag<p><br>";
        }
        return $res;
    }
}