<?php

namespace iutnc\touiter\action;

use iutnc\touiter\db\ConnectionFactory;

class ActionMenuTag extends Action
{
    public function execute(): string
    {
        $res = "<h1 class='rep'>Tag: </h1>";
        $bdo = ConnectionFactory::makeConnection();

        // Requête SQL pour obtenir les tags qui sont dans les abonnements de l'utilisateur connecté
        $sql = "SELECT tag.id_tag as idTag, label, description from tag inner join user2tag on tag.id_tag=user2tag.id_tag where id_user=?";
        $resultSet = $bdo->prepare($sql);
        $resultSet->bindParam(1, $_SESSION['id']);
        $resultSet->execute();

        // Vérifie s'il existe des tags auxquels l'utilisateur est abonné
        if ($row = $resultSet->fetch()) {
            do {
                $abonnement = "Se désabonner";

                // Affiche le nom du tag et le lien vers la page du tag
                $res .= "<div class='tag'>
                    <a href='index.php?action=page-tag&id_tag=" . $row['idTag'] . "'><h4 class='labeltag'>" . $row['label'] . "</h4></a><br>";

                // Ajoute la description du tag s'il existe
                if (!is_null($row['description'])) {
                    $res .= "<p>" . $row['description'] . "</p><br>";
                } else {
                    $res .= "<p>Pas de description</p><br>";
                }

                // Formulaire pour se désabonner au tag
                $res .= "<form  method='post' action='index.php?action=subscribeTag&id_tag=" . $row['idTag'] . "'>
                    <input class='abb' type='submit' name='subscribeTag' value='$abonnement'><br>
                </form>";

                $res .= "</div>";

            } while ($row = $resultSet->fetch());
        } else {
            // S'il ne s'est abonné à aucun tag alors on affiche ce message
            $res .= "<p class='aucunTag'>Vous êtes abonné à aucun tag<p><br>";
        }

        // Retourne le résultat HTML
        return $res;
    }
}
