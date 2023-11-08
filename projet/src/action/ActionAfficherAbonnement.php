<?php

namespace iutnc\deefy\action;

use iutnc\deefy\db\ConnectionFactory;

class ActionAfficherAbonnement extends Action
{

    public function execute(): string
    {
        $res="";
        $sql="SELECT * from user inner join subscribe where user.id_user=publisher and subscribe.subscriber=?";
        $bdd=ConnectionFactory::makeConnection();
        $resultSet=$bdd->prepare($sql);
        $resultSet->bindParam(1,$_SESSION['id']);
        $resultSet->execute();
        while ($row=$resultSet->fetch()){
            
        }
    }
}