<?php

namespace iutnc\touiter\action;

use iutnc\touiter\db\ConnectionFactory;

class ActionAfficherTouiteTag extends Action
{
    public function execute(): string
    {
        // Chaîne de résultat à retourner
        $res = "";
        // Requête SQL pour obtenir les touites associé à un tag
        $sql = "SELECT * FROM Touite JOIN User ON User.id_user = Touite.id_user JOIN Touite2tag ON touite2tag.id_touite = Touite.id_touite WHERE touite2tag.id_tag=? ORDER BY Touite.date DESC";

        $bdd = ConnectionFactory::makeConnection();
        $resultSet = $bdd->prepare($sql);
        $resultSet->bindParam(1, $_GET['id_tag']);
        $resultSet->execute();

        // On parcourt les résultats et construit la chaîne de résultat HTML
        while ($row = $resultSet->fetch()) {
            $res .=
                "<fieldset class='touite-box'>
                <legend><a href='?action=page-user&iduser=" . $row['id_user'] . "'><h2>&nbsp&nbsp" . $row['firstname'] . " " . $row['lastname'] . "</h2></a></legend><p>" . $row['message'] . "</p><br>";

            // On ajoute une image s'il y en a une
            if (!is_null($row['path'])) {
                $res .= "<img src=" . $row['path'] . " alt=" . $row['description'] . "><br>";
            }

            // On ajoute un lien pour voir plus de détails sur le touite
            $res .= "<a href=index.php?action=voirPlus&id=" . $row['id_touite'] . " class='voirplus'><img src='icon/more.png' style='width:30px;margin:0;'></a></fieldset><br>";
        }

        // Retourne le résultat HTML
        return $res;
    }
}
