<?php

namespace iutnc\deefy\action;

use iutnc\deefy\db\ConnectionFactory;

class ActionPageTag extends Action
{

    public function execute(): string
    {
        $res="";
        $bdo=ConnectionFactory::makeConnection();
        $sql="SELECT label from tag inner join user2tag on tag.id_tag=user2tag.id_tag where id_user=?";
        $resultSet=$bdo->prepare($sql);
        $resultSet->bindParam(1,$_SESSION['id']);
        $resultSet->execute();
        while ($row=$resultSet->fetch()){
            $res.="<div class =tag>
                <p>".$row['label']."<p><br>";
            if(!is_null($row['description'])){
                $resultSet.="<p>".$row['description']."</p><br>";
            }
            $resultSet.="</div>";
        }
        return $res;
    }
}