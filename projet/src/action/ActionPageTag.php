<?php

namespace iutnc\touiter\action;

use iutnc\touiter\db\ConnectionFactory;

class ActionPageTag extends Action
{
    public function execute(): string
    {
        // Chaîne de résultat à retourner
        $res = "";
        $bdo = ConnectionFactory::makeConnection();

        // Requête SQL pour obtenir les informations sur le tag à partir de l'id du tag qui est dans la requête GET
        $sql = "SELECT id_tag as idTag, label, description from TAG where id_tag=?";
        $resultSet = $bdo->prepare($sql);
        $resultSet->bindParam(1, $_GET['id_tag']);
        $resultSet->execute();
        $row = $resultSet->fetch();

        // Requête SQL pour vérifier si l'utilisateur est abonné au tag
        $sql2 = "select * from USER2TAG where id_tag=? and id_user=?";
        $resultSet2 = $bdo->prepare($sql2);
        $resultSet2->bindParam(1, $row['idTag']);
        $resultSet2->bindParam(2, $_SESSION['id']);
        $resultSet2->execute();

        // Texte du bouton d'abonnement par défaut
        $abonnement = "S'abonner";
        if ($resultSet2->fetch()) {
            // On change le texte du bouton si l'utilisateur est déjà abonné
            $abonnement = "Se désabonner";
        }

        // Construction du HTML pour le tag
        $res .= "<div class='tag'><a href='index.php?action=page-tag&id_tag=" . $row['idTag'] . "'><h4 class='labeltag'>" . $row['label'] . "</h4></a><br>";

        // Ajoute la description du tag s'il existe
        if (!is_null($row['description'])) {
            $res .= "<p>" . $row['description'] . "</p><br>";
        } else {
            $res .= "<p>Pas de description</p><br>";
        }

        // Formulaire d'abonnement/désabonnement au tag
        $res .= "<form  method='post' action='index.php?action=subscribeTag&id_tag=" . $row['idTag'] . "'>
               <input class='abb' type='submit' name='subscribeTag' value='$abonnement'><br>
               </form>";

        $res .= "</div>";

        // Affiche des touites associé à ce tag
        $res .= "<h1 class='rep'>Touites: </h1>";

        $act = new ActionAfficherTouiteTag();
        $res .= $act->execute();

        // Retourne le résultat HTML
        return $res;
    }
}
