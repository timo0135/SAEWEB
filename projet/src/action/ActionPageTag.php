<?php

namespace iutnc\deefy\action;

use iutnc\deefy\db\ConnectionFactory;

class ActionPageTag extends Action
{

    public function execute(): string
    {
        $res="";
        $bdo=ConnectionFactory::makeConnection();
        $sql="SELELCT label from tag order by id_tag";
        $resultSet=$bdo->prepare($sql);
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