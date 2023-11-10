<?php

namespace iutnc\deefy\manip;

use iutnc\deefy\action\Action;
use iutnc\deefy\db\ConnectionFactory;

class ManipDislike
{
    public function execute(): void
    {
        $bdd = ConnectionFactory::makeConnection();

        if(isset($_SESSION['id']) && isset($_GET['id'])){
            $id_user = $_SESSION['id'];
            $id_touite = $_GET['id'];

            $sql = "SELECT * FROM `like` WHERE id_touite=:id_touite AND id_user=:id_user";
            $resultSet = $bdd->prepare($sql);
            $resultSet->bindParam(':id_touite', $id_touite);
            $resultSet->bindParam(':id_user', $id_user);
            $resultSet->execute();

            if ($resultSet->fetch()) {
                $sql = "UPDATE `like` SET `like`=0 WHERE id_touite=:id_touite AND id_user=:id_user";
                $resultSet = $bdd->prepare($sql);
                $resultSet->bindParam(':id_touite', $id_touite);
                $resultSet->bindParam(':id_user', $id_user);
                $resultSet->execute();
            } else {
                $sql = "INSERT INTO `like` (id_user, id_touite, `like`) VALUES (:id_user, :id_touite, 0)";
                $resultSet = $bdd->prepare($sql);
                $resultSet->bindParam(':id_user', $id_user);
                $resultSet->bindParam(':id_touite', $id_touite);
                $resultSet->execute();
            }
        } else {
            echo "<script>alert('Tu ne peux pas disliker si tu n\'est pas connecté');</script>";
        }
    }

}