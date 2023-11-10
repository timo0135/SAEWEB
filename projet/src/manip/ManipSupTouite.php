<?php

namespace iutnc\deefy\manip;

use iutnc\deefy\db\ConnectionFactory;

class ManipSupTouite
{
    public function execute(): void
    {
        $bdd = ConnectionFactory::makeConnection();
        $user = "select role from user where id_user=?";
        $res = $bdd->prepare($user);
        $res->bindParam(1, $_SESSION['id']);
        $res->execute();
        $us = $res->fetch();

        if (isset($_GET['id']) && $us['role'] == 100) {
            $id_touite = $_GET['id'];

            $sql = "DELETE FROM touite2tag WHERE id_touite=:id_touite";
            $resultSet = $bdd->prepare($sql);
            $resultSet->bindParam(':id_touite', $id_touite);
            $resultSet->execute();

            $sql = "DELETE FROM `like` WHERE id_touite=:id_touite";
            $resultSet = $bdd->prepare($sql);
            $resultSet->bindParam(':id_touite', $id_touite);
            $resultSet->execute();

            $sql = "DELETE FROM touite WHERE answer=:id_touite";
            $resultSet = $bdd->prepare($sql);
            $resultSet->bindParam(':id_touite', $id_touite);
            $resultSet->execute();

            $sql = "DELETE FROM touite WHERE id_touite=:id_touite";
            $resultSet = $bdd->prepare($sql);
            $resultSet->bindParam(':id_touite', $id_touite);
            $resultSet->execute();

        }
    }
}