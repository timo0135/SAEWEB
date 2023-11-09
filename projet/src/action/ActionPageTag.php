<?php

namespace iutnc\deefy\action;

use iutnc\deefy\db\ConnectionFactory;

class ActionPageTag extends Action
{

    public function execute(): string
    {
        $res="";
        $bdo=ConnectionFactory::makeConnection();
        $sql="SELECT label,description from tag inner join user2tag on tag.id_tag=user2tag.id_tag where id_tag=?";
        $resultSet=$bdo->prepare($sql);
        $resultSet->bindParam(1,$_SESSION['id']);
        $resultSet->execute();
        while ($row=$resultSet->fetch()){
            $res.="<div class =tag>
                <p id='labeltag'>".$row['label']."<p><br>";
            if(!is_null($row['description'])){
                $res.="<p>".$row['description']."</p><br>";
            }
            $res.="</div>";
        }
        return $res;
    }
}