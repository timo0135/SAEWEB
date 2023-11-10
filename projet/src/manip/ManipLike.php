<?php

namespace iutnc\touiter\manip;

use iutnc\touiter\action\Action;
use iutnc\touiter\db\ConnectionFactory;

class ManipLike
{
    public function execute(): void
    {
        $bdd = ConnectionFactory::makeConnection();

        // Vérifie si l'utilisateur est connecté et si l'id du touite est dans la requête
        if(isset($_SESSION['id']) && isset($_GET['id'])){
            $id_user = $_SESSION['id'];
            $id_touite = $_GET['id'];

            // Vérifie si l'utilisateur a déjà like ou dislike ce touite
            $sql = "SELECT * FROM `LIKE` WHERE id_touite=:id_touite AND id_user=:id_user";
            $resultSet = $bdd->prepare($sql);
            $resultSet->bindParam(':id_touite', $id_touite);
            $resultSet->bindParam(':id_user', $id_user);
            $resultSet->execute();

            // Si l'utilisateur a déjà like ou dislike, on met à jour le statut du like/dislike
            if ($resultSet->fetch()) {
                $sql = "UPDATE `LIKE` SET `like`=1 WHERE id_touite=:id_touite AND id_user=:id_user";
                $resultSet = $bdd->prepare($sql);
                $resultSet->bindParam(':id_touite', $id_touite);
                $resultSet->bindParam(':id_user', $id_user);
                $resultSet->execute();
            } else {
                // Si l'utilisateur n'a pas encore like ou dislike, on insère un nouveau dislike
                $sql = "INSERT INTO `LIKE` (id_user, id_touite, `like`) VALUES (:id_user, :id_touite, 1)";
                $resultSet = $bdd->prepare($sql);
                $resultSet->bindParam(':id_user', $id_user);
                $resultSet->bindParam(':id_touite', $id_touite);
                $resultSet->execute();
            }
        } else {
            // Si l'utilisateur n'est pas connecté, on affiche un message
            echo "<script>alert('Tu ne peux pas liker si tu n\'est pas connecté');</script>";
        }
    }
}
