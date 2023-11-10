<?php

namespace iutnc\touiter\action;

use iutnc\touiter\db\ConnectionFactory;

class ActionProfilUser extends Action
{
    public function execute(): string
    {
        // Chaîne de résultat à retourner
        $res = "";

        // Vérifie s'il y a un message de succès dans la requête GET et affiche le message correspondant
        if (isset($_GET['succ'])) {
            switch ($_GET['succ']) {
                case '1':
                    $res .= "<div class='succ'>Vous vous êtes bien désabonné</div>";
                    break;
                case '2':
                    $res .= "<div class='succ'>Vous vous êtes bien abonné</div>";
                    break;
            }
        }

        // Vérifie si l'utilisateur est connecté et qu'il est différent que l'id donnné dans la requête GET ou si l'utilisateur n'est pas connecté
        if (isset($_GET['iduser']) && ((isset($_SESSION['id']) && $_GET['iduser'] != $_SESSION['id']) || !isset($_SESSION['id']))) {
            // Requête SQL pour obtenir les informations sur l'utilisateur à afficher
            $sql = "SELECT * from USER where id_user=?;";
            $requete = "select publisher from SUBSRIBE where subsriber = ?;";
            $bdd = ConnectionFactory::makeConnection();
            $resultSet = $bdd->prepare($sql);
            $resultSet->bindParam(1, $_GET['iduser']);
            $resultSet->execute();
            $res .= "<div class='haut-page'>";
            $row = $resultSet->fetch();

            // Construit la partie HTML
            $res .= "<div class='user'>";
            $res .= "<div class='user-name'>" . $row['firstname'] . " " . $row['lastname'] . "</div>";
            $res .= "<div class='user-email'>" . $row['email'] . "</div>";

            // Vérifie si l'utilisateur connecté est abonné à l'utilisateur actuel
            $resultSet2 = $bdd->prepare($requete);
            $resultSet2->bindParam(1, $_SESSION['id']);
            $resultSet2->execute();
            $res2 = "";
            if ($rw = $resultSet2->fetch()) {
                do {
                    // S'il est abbonné
                    if ($rw['publisher'] == $_GET['iduser']) {
                        $res2 = "<a href='index.php?action=subscribe&iduser=" . $_GET['iduser'] . "'><button class='abonnement'>Se désabonner </button></a>";
                        break;
                    } else {
                        // S'il ne l'est pas
                        $res2 = "<a href='index.php?action=subscribe&iduser=" . $_GET['iduser'] . "'><button class='abonnement'>S'abonner </button></a>";
                    }
                } while ($rw = $resultSet2->fetch());
            } else {
                $res2 = "<a href='index.php?action=subscribe&iduser=" . $_GET['iduser'] . "'><button class='abonnement'>S'abonner </button></a>";
            }
            $res .= $res2;
            $res .= "</div>";

            $res .= "</div>";

            // Ajoute l'en-tête pour les touites associés à cet utilisateur
            $res .= "<h1 class='rep'>Touites: </h1>";

            // Requête SQL pour obtenir les touites de l'utilisateur actuel ou de l'utilisateur spécifié
            $sql = "select distinct TOUITE.* from TOUITE inner join SUBSRIBE on SUBSRIBE.publisher = TOUITE.id_user where SUBSRIBE.subsriber = ? union select distinct TOUITE.* from TOUITE inner join TOUITE2TAG on TOUITE.id_touite = TOUITE2TAG.id_touite inner join USER2TAG on TOUITE2TAG.id_tag = USER2TAG.id_tag where USER2TAG.id_user = ? order by date desc;";
            $resultSet = $bdd->prepare($sql);
            $resultSet->bindParam(1, $_GET['iduser']);
            $resultSet->bindParam(2, $_GET['iduser']);
            $resultSet->execute();

            // Parcourir les résultats et construire la partie HTML des touites associés à cet utilisateur
            while ($row = $resultSet->fetch()) {
                $sql2 = "select firstname, lastname from USER where id_user =?;";
                $result = $bdd->prepare($sql2);
                $result->bindParam(1, $row['id_user']);
                $result->execute();
                $rw = $result->fetch();
                $res .= "<fieldset class='touite-box'>
                    <legend><a href='?action=page-user&iduser=" . $row['id_user'] . "'><h2>&nbsp&nbsp" . $rw['firstname'] . " " . $rw['lastname'] . "</h2></a></legend><p>" . $row['message'] . "</p><br>";
                if (!is_null($row['path'])) {
                    $res .= "<img class='imagetouite' src=" . $row['path'] . " alt=" . $row['description'] . "><br>";
                }
                $res .= "<a href=index.php?action=voirPlus&id=" . $row['id_touite'] . " class='voirplus'>Voir plus</a></fieldset><br>";
            }
        } else {
            // Si l'id de l'utilisateur à afficher n'est pas présent dans la requête GET, afficher le profil de l'utilisateur connecté
            $sql = "SELECT * from USER where id_user=?;";
            $bdd = ConnectionFactory::makeConnection();
            $resultSet = $bdd->prepare($sql);
            $resultSet->bindParam(1, $_SESSION['id']);
            $resultSet->execute();
            $res .= "<div class='haut-page'>";
            $row = $resultSet->fetch();
            $res .= "<div class='user'>";
            $res .= "<div class='user-name'>" . $row['firstname'] . " " . $row['lastname'] . "</div>";
            $res .= "<div class='user-email'>" . $row['email'] . "</div>";
            $res .= "</div>";

            $res .= "</div>";

            // Ajouter l'en-tête pour les touites associés à l'utilisateur connecté
            $res .= "<h1 class='rep'>Touites: </h1>";

            // Requête SQL pour obtenir les touites de l'utilisateur connecté
            $sql = "select distinct TOUITE.* from TOUITE inner join SUBSRIBE on SUBSRIBE.publisher = TOUITE.id_user where SUBSRIBE.subsriber = ? union select distinct TOUITE.* from TOUITE inner join TOUITE2TAG on TOUITE.id_touite = TOUITE2TAG.id_touite inner join USER2TAG on TOUITE2TAG.id_tag = USER2TAG.id_tag where USER2TAG.id_user = ? order by date desc;";
            $resultSet = $bdd->prepare($sql);
            $resultSet->bindParam(1, $_SESSION['id']);
            $resultSet->bindParam(2, $_SESSION['id']);
            $resultSet->execute();

            // Parcourir les résultats et construire la partie HTML des touites associés à l'utilisateur connecté
            while ($row = $resultSet->fetch()) {
                $sql2 = "select firstname, lastname from USER where id_user =?;";
                $result = $bdd->prepare($sql2);
                $result->bindParam(1, $row['id_user']);
                $result->execute();
                $rw = $result->fetch();
                $res .= "<fieldset class='touite-box'>
                    <legend><a href='?action=page-user&iduser=" . $row['id_user'] . "'><h2>&nbsp&nbsp" . $rw['firstname'] . " " . $rw['lastname'] . "</h2></a></legend><p>" . $row['message'] . "</p><br>";
                if (!is_null($row['path'])) {
                    $res .= "<img src=" . $row['path'] . " alt=" . $row['description'] . "><br>";
                }
                $res .= "<a href=index.php?action=voirPlus&id=" . $row['id_touite'] . " class='voirplus'>Voir plus</a></fieldset><br>";
            }
        }
        // Retourne le résultat HTML
        return $res;
    }
}
