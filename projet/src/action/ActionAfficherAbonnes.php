<?php

namespace iutnc\deefy\action;

use iutnc\deefy\db\ConnectionFactory;

class ActionAfficherAbonnes extends Action
{

    public function execute(): string
    {
        // Initialise une chaîne vide pour stocker le résultat
        $res="";

        // Requête SQL pour récupérer les utilisateurs qui sont abonné à l'utilisateur actuel
        $sql="SELECT * from user inner join subsribe where user.id_user=subsriber and subsribe.publisher=?";
        $bdd=ConnectionFactory::makeConnection();
        $resultSet=$bdd->prepare($sql);
        $resultSet->bindParam(1,$_SESSION['id']);
        $resultSet->execute();

        // Construit le HTML pour afficher les abonnés
        $res.= "<h2 class='rep'>Abonnés:</h2>";
        if($row=$resultSet->fetch()){
            do{
                // Construction de la structure HTML pour chaque abonnés
                $res .= "<div class='haut-page'>";
                $res .= "<a href='?action=page-user&iduser=".$row['id_user']."'>";
                $res .= "<div class='user'>";
                $res .= "<div class='user-name'>".$row['firstname']." ".$row['lastname']."</div>";
                $res .= "<div class='user-email'>".$row['email']."</div>";
                $res .= "</div>";
                $res .= "</a>";
                $res .= "</div>";
            } while ($row=$resultSet->fetch());
        }else{
            // Si l'utilisateur n'a pas d'abonnés alors on affiche ce message
            $res.="<div class='aucunTag'>Vous n'avez pas d'abonnés</div>";
        }
        // Retourne le résultat HTML
        return $res;
    }
}