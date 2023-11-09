<?php

namespace iutnc\deefy\action;

use iutnc\deefy\db\ConnectionFactory;

class ActionAfficherTouiteTag extends Action
{

    public function execute(): string
    {
        $res="";
        $sql="SELECT * FROM Touite JOIN User ON User.id_user = Touite.id_user JOIN Touite2tag ON touite2tag.id_touite = Touite.id_touite WHERE touite2tag.id_tag=? ORDER BY Touite.date DESC";
        $bdd=ConnectionFactory::makeConnection();
        $resultSet=$bdd->prepare($sql);
        $resultSet->bindParam(1,$_GET['id_tag']);
        $resultSet->execute();
        while ($row=$resultSet->fetch()){
            $res.=
            "<fieldset class='touite-box'>
                <legend><a href='?action=page-user&iduser=".$row['id_user']."'><h2>&nbsp&nbsp".$row['firstname']." ".$row['lastname']."</h2></a></legend><p>".$row['message']."</p><br>";
            if(!is_null($row['path'])){
                $res.="<img src=".$row['path']." alt=".$row['description']."><br>";
            }
            $res.="<a href=index.php?action=voirPlus&id=".$row['id_touite']." class='voirplus'>Voir plus</a></fieldset><br>";
        }
        return $res;
    }
}