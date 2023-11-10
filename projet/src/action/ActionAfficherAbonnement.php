<?php

namespace iutnc\touiter\action;

use iutnc\touiter\db\ConnectionFactory;

class ActionAfficherAbonnement extends Action
{

    public function execute(): string
    {
        // Initialise une chaîne vide pour stocker le résultat
        $res = "";

        // Requête SQL pour récupérer les utilisateurs auxquels l'utilisateur actuel est abonné
        $sql = "SELECT * FROM user INNER JOIN subscribe ON user.id_user = publisher WHERE subscribe.subscriber=?";
        $bdd = ConnectionFactory::makeConnection();
        $resultSet = $bdd->prepare($sql);

        // Utiliser la session pour récupérer l'id de l'utilisateur actuel
        $resultSet->bindParam(1, $_SESSION['id']);
        $resultSet->execute();

        // Construit le HTML pour afficher les abonnements
        $res .= "<h2 class='rep'>Abonnement:</h2>";

        // Vérifie s'il y a des résultats dans la requête
        if ($row = $resultSet->fetch()) {
            do {
                // Construction de la structure HTML pour chaque abonnement
                $res .= "<div class='haut-page'>";
                $res .= "<a href='?action=page-user&iduser=" . $row['id_user'] . "'>";
                $res .= "<div class='user'>";
                $res .= "<div class='user-name'>" . $row['firstname'] . " " . $row['lastname'] . "</div>";
                $res .= "<div class='user-email'>" . $row['email'] . "</div>";
                $res .= "</div>";
                $res .= "</a>";
                $res .= "</div>";
            } while ($row = $resultSet->fetch());
        } else {
            // Si l'utilisateur n'a pas d'abonnements alors on affiche ce message
            $res .= "<div class='aucunTag'>Vous n'avez pas d'abonnement</div>";
        }

        // Retourne le résultat HTML
        return $res;
    }
}
