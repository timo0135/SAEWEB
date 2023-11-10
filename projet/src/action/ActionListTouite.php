<?php

namespace iutnc\touiter\action;

use iutnc\touiter\db\ConnectionFactory;
use PDO;

class ActionListTouite extends Action
{
    private $listTouite;
    private $resultSet;

    public function execute(): string
    {

        $page = 1;
        if(!empty($_GET['page'])) {
            $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
            if(false === $page) {
                $page = 1;
            }
        }
        $touite_par_page=5;
        $offset=($page-1)*$touite_par_page;

        $bdd = ConnectionFactory::makeConnection();

        // On construit la requête SQL selon si l'utilisateur est connecté ou non
        if (!isset($_SESSION['id'])) {
            // S'il est pas connecté alors on récupère tout les touites dans l'ordre de la date
            $this->listTouite = "select * from TOUITE order by date desc LIMIT :offset, :touiteparpage";
            $this->resultSet = $bdd->prepare($this->listTouite);
            $this->resultSet->bindParam(':offset',$offset,PDO::PARAM_INT);
            $this->resultSet->bindParam(':touiteparpage',$touite_par_page,PDO::PARAM_INT);
        } else {
            // S'il est connecté alors on récupère tout les touites sauf les touites de ses abonnements (tag et user)
            $this->listTouite = "select * from TOUITE where id_touite not in(SELECT id_touite FROM TOUITE inner join SUBSRIBE on SUBSRIBE.publisher=TOUITE.id_user where subsriber= :id_session ) and id_touite not in(SELECT TOUITE.id_touite FROM TOUITE INNER JOIN TOUITE2TAG on TOUITE2TAG.id_touite=TOUITE.id_touite INNER JOIN USER2TAG on USER2TAG.id_tag=TOUITE2TAG.id_tag where USER2TAG.id_user= :id_session ) order by date desc LIMIT :offset, :touiteparpage";
            $this->resultSet = $bdd->prepare($this->listTouite);
            $this->resultSet->bindParam(':id_session',$_SESSION['id'],PDO::PARAM_INT);
            $this->resultSet->bindParam(':offset',$offset,PDO::PARAM_INT);
            $this->resultSet->bindParam(':touiteparpage',$touite_par_page,PDO::PARAM_INT);
        }


        // On exécute la requête SQL
        $this->resultSet->execute();


        // On initialise la chaîne de résultat
        $affichage = "";

        // Si l'utilisateur est connecté et 'incremente' est 0 alors on affiche les résultats de ActionTouiteTagSub
        if (isset($_SESSION['id']) && $page===1) {
            $perso = new ActionTouiteTagSub();
            $affichage = $perso->execute();
        }

        $count=0;
        while ($row = $this->resultSet->fetch()) {
            $count++;
            $user = "select firstname, lastname from USER where id_user=?";
            $res = $bdd->prepare($user);
            $res->bindParam(1, $row["id_user"]);
            $res->execute();
            $us = $res->fetch();

            $affichage .= "
            <fieldset class='touite-box'>";

            if (!empty($row['answer'])) {
                $commande = "SELECT USER.id_user,firstname,lastname FROM USER 
                JOIN TOUITE ON TOUITE.id_user=USER.id_user 
                WHERE id_touite=" . $row['answer'];
                $res = $bdd->query($commande);
                if($row2 = $res->fetch()) {
                    $affichage .= "
                <p class='top-right'>
                    reponse à 
                    <a href='?action=voirPlus&id=" . $row['answer'] . "'>
                        &nbsp" . $row2['firstname'] . "&nbsp" . $row2['lastname'] . "
                    </a>
                </p>";
                }
            }

            $affichage .= "
                <legend>
                    <a href='?action=page-user&iduser=" . $row['id_user'] . "'>
                        <h2>&nbsp&nbsp" . $us['firstname'] . " " . $us['lastname'] . "</h2>
                    </a>
                </legend>
                <p>" . $row['message'] . "</p><br>";

            if (!is_null($row['path'])) {
                $affichage = $affichage . "<img class='imagetouite' src='" . $row['path'] . "' alt='" . $row['description'] . "' ><br>";
            }

            $affichage = $affichage . "
            <a href=index.php?action=voirPlus&id=" . $row['id_touite'] . " class='voirplus'><img src='icon/more.png' style='width:30px;margin:0;'></a>
            </fieldset><br>";
        }

        // Ajouter des liens de pagination

        $affichage .= "<div class='paginer'>";
        if($page>1) {
            $pagemoins=$page-1;
            $affichage .= "<a href=index.php?page=$pagemoins>Précédent</a>";
        }
        if($count>=5) {
            $pageplus = $page + 1;
            $affichage .= "<a href=index.php?page=$pageplus>&nbspSuivant</a>";
        }
        $affichage.="</div>";

        // Retourne le résultat HTML
        return $affichage;
    }
}
