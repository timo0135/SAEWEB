<?php

namespace iutnc\deefy\action;

use iutnc\deefy\db\ConnectionFactory;

class ActionRechercherTag extends Action
{

    public function execute(): string
    {
        $res="";
        $sql="SELECT label,description from tag where label = ?";
        $bdd=ConnectionFactory::makeConnection();
        $resultSet=$bdd->prepare($sql);
        $resultSet->bindParam(1,$_POST['recherche']);
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