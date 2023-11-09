<?php

namespace iutnc\deefy\action;

use iutnc\deefy\db\ConnectionFactory;

class ActionAfficherAbonnement extends Action
{

    public function execute(): string
    {
        $res="";
        $sql="SELECT * from user inner join subsribe where user.id_user=publisher and subsribe.subsriber=?";
        $bdd=ConnectionFactory::makeConnection();
        $resultSet=$bdd->prepare($sql);
        $resultSet->bindParam(1,$_SESSION['id']);
        $resultSet->execute();
        $res.= "<h2 class='rep'>Abonnement:</h2>";
        while ($row=$resultSet->fetch()){
            $res .= "<div class='haut-page'>";
            $res .= "<a href='?action=page-user&iduser=".$row['id_user']."'>";
            $res .= "<div class='user'>";
            $res .= "<div class='user-name'>".$row['firstname']." ".$row['lastname']."</div>";
            $res .= "<div class='user-email'>".$row['email']."</div>";
            $res .= "</div>";
            $res .= "</a>";
            $res .= "</div>";
        }
        return $res;
    }
}