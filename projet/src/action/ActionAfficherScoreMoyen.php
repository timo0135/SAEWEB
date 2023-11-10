<?php

namespace iutnc\touiter\action;

use iutnc\touiter\db\ConnectionFactory;

class ActionAfficherScoreMoyen extends Action
{
    public function execute(): string
    {
        // Chaîne de résultat à retourner
        $res = "";
        $bdd = ConnectionFactory::makeConnection();

        // Requête SQL pour obtenir les id_touite de l'utilisateur
        $sql = "Select id_touite from touite where id_user=?";
        // Requête SQL pour compter les likes
        $nbLike = "Select count(*) as count from `like` where id_touite=? and `like`.like=1";
        // Requête SQL pour compter les dislikes
        $nbDislike = "Select count(*) as count from `like` where id_touite=? and `like`.like=0";

        // On prépare la prmière requête
        $resultSet = $bdd->prepare($sql);
        $resultSet->bindParam(1, $_SESSION['id']);
        $resultSet->execute();

        // On initialise les variables
        $longeur = 0;
        $like = 0;
        $dislike = 0;

        // Boucle pour traiter chaque résultat de la première requête
        while ($row = $resultSet->fetch()) {
            // On incrémente la longueur pour chaque touite
            $longeur++;

            // On prépare et exécute les requêtes pour compter les likes et dislikes
            $resultLike = $bdd->prepare($nbLike);
            $resultDislike = $bdd->prepare($nbDislike);
            $resultLike->bindParam(1, $row['id_touite']);
            $resultDislike->bindParam(1, $row['id_touite']);
            $resultLike->execute();
            $resultDislike->execute();

            // On récupère les résultats et met à jour les compteurs
            $rowLike = $resultLike->fetch();
            $rowDislike = $resultDislike->fetch();
            $like += $rowLike['count'];
            $dislike += $rowDislike['count'];
        }

        // On construit le résultat HTML en fonction des statistiques
        $res .= "<fieldset class='touite-box'><legend><h1>Statistiques</h1></legend>";

        if ($longeur === 0) {
            $res .= "<p>Vous n'avez toujours pas posté de touite. Cliquez ici pour poster un touite.</p>";
        } else {
            $moyenneLike = $like / $longeur;
            $moyenneDislike = $dislike / $longeur;
            $moyenneLike = round($moyenneLike, 2);
            $moyenneDislike = round($moyenneDislike, 2);
            $res .= "<p>Vous avez posté $longeur touite.<br><br>Vous avez en moyenne obtenu $moyenneLike like et $moyenneDislike dislike</p><br>";
        }

        $res .= "</fieldset>";

        // Retourne le résultat HTML
        return $res;
    }
}
