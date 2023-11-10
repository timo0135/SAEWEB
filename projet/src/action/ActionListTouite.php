<?php

namespace iutnc\touiter\action;

use iutnc\touiter\db\ConnectionFactory;

class ActionListTouite extends Action
{
    private $listTouite;
    private $resultSet;

    public function execute(): string
    {
        // On initialise la session si 'incremente' n'est pas défini
        if (!isset($_SESSION['incremente'])) {
            $_SESSION['incremente'] = 0;
        }

        $bdd = ConnectionFactory::makeConnection();

        // On construit la requête SQL selon si l'utilisateur est connecté ou non
        if (!isset($_SESSION['id'])) {
            // S'il est pas connecté alors on récupère tout les touites dans l'ordre de la date
            $this->listTouite = "select * from touite order by date desc";
            $this->resultSet = $bdd->prepare($this->listTouite);
        } else {
            // S'il est connecté alors on récupère tout les touites sauf les touites de ses abonnements (tag et user)
            $this->listTouite = "select * from touite where id_touite not in(SELECT id_touite FROM touite inner join subsribe on subsribe.publisher=touite.id_user where subsriber=?) and id_touite not in(SELECT touite.id_touite FROM touite INNER JOIN touite2tag on touite2tag.id_touite=touite.id_touite INNER JOIN user2tag on user2tag.id_tag=touite2tag.id_tag where user2tag.id_user=?) order by date desc";
            $this->resultSet = $bdd->prepare($this->listTouite);
            $this->resultSet->bindParam(1, $_SESSION['id']);
            $this->resultSet->bindParam(2, $_SESSION['id']);
        }

        // On exécute la requête SQL
        $this->resultSet->execute();

        // On initialise la chaîne de résultat
        $affichage = "";

        // Si l'utilisateur est connecté et 'incremente' est 0 alors on affiche les résultats de ActionTouiteTagSub
        if (isset($_SESSION['id']) && $_SESSION['incremente'] === 0) {
            $perso = new ActionTouiteTagSub();
            $affichage = $perso->execute();
        }

        // On initialise un compteur
        $i = 0;

        // On vérifie si i est inférieur à incremente
        if ($i < $_SESSION['incremente']) {
            // On parcourt les résultats
            while ($row = $this->resultSet->fetch()) {
                // Si i est égale à incremente alors on arrête
                if ($i === $_SESSION['incremente']) {
                    break;
                }
                $i++;
                $user = "select firstname, lastname from user where id_user=?";
                $res = $bdd->prepare($user);
                $res->bindParam(1, $row["id_user"]);
                $res->execute();
                $us = $res->fetch();
            }
        }

        while ($row = $this->resultSet->fetch()) {
            if ($i === $_SESSION['incremente'] + 5) {
                break;
            }
            $i++;
            $user = "select firstname, lastname from user where id_user=?";
            $res = $bdd->prepare($user);
            $res->bindParam(1, $row["id_user"]);
            $res->execute();
            $us = $res->fetch();

            $affichage .= "
            <fieldset class='touite-box'>";

            if (!empty($row['answer'])) {
                $commande = "SELECT User.id_user,firstname,lastname FROM User 
                JOIN touite ON touite.id_user=user.id_user 
                WHERE id_touite=" . $row['answer'];
                $res = $bdd->query($commande);
                $row2 = $res->fetch();
                $affichage .= "
                <p class='top-right'>
                    reponse à 
                    <a href='?action=voirPlus&id=" . $row['answer'] . "'>
                        &nbsp" . $row2['firstname'] . "&nbsp" . $row2['lastname'] . "
                    </a>
                </p>";
            }

            $affichage .= "
                <legend>
                    <a href='?action=page-user&iduser=" . $row['id_user'] . "'>
                        <h2>&nbsp&nbsp" . $us['firstname'] . " " . $us['lastname'] . "</h2>
                    </a>
                </legend>
                <p>" . $row['message'] . "</p><br>";

            if (!is_null($row['path'])) {
                $affichage = $affichage . "<img src='" . $row['path'] . "' alt='" . $row['description'] . "' class='imagetouite'><br>";
            }

            $affichage = $affichage . "
            <a href=index.php?action=voirPlus&id=" . $row['id_touite'] . " class='voirplus'><img src='icon/more.png' style='width:30px;margin:0;'></a>
            </fieldset><br>";
        }

        // Ajouter des liens de pagination
        $affichage .= "<div class='paginer'>";
        if ($_SESSION['incremente'] > 0) {
            $affichage .= "<a href=index.php?action=paginerTouite&augmenter=faux>Précédent</a>";
        }

        if ($i === $_SESSION['incremente'] + 5) {
            $affichage .= "<a href=index.php?action=paginerTouite&augmenter=vrai>&nbspSuivant</a>";
        }

        return $affichage; // Retourner la chaîne de résultat
    }
}
