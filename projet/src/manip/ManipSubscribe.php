<?php

namespace iutnc\touiter\manip;

use iutnc\touiter\db\ConnectionFactory;

class ManipSubscribe
{
    // Méthode pour s'abonner ou se désabonner d'un tag
    public static function subscribeTag($id_tag, $id)
    {
        // Requête pour vérifier si l'utilisateur est déjà abonné au tag
        $sql = "SELECT * FROM USER2TAG WHERE id_tag=? AND id_user=?";
        $bdd = ConnectionFactory::makeConnection();
        $resultSet = $bdd->prepare($sql);
        $resultSet->bindParam(1, $id_tag);
        $resultSet->bindParam(2, $id);
        $resultSet->execute();

        // Si l'utilisateur est déjà abonné, on le désabonne
        if ($resultSet->fetch()) {
            $sql = "DELETE FROM USER2TAG WHERE id_tag=? AND id_user=?";
            $resultSet = $bdd->prepare($sql);
            $resultSet->bindParam(1, $id_tag);
            $resultSet->bindParam(2, $id);
            $resultSet->execute();
            header('location:index.php?succ=4');
            exit();
        } else {
            // Sinon, on l'abonne au tag
            $sql = "INSERT INTO USER2TAG VALUES (?, ?)";
            $resultSet = $bdd->prepare($sql);
            $resultSet->bindParam(1, $id);
            $resultSet->bindParam(2, $id_tag);
            $resultSet->execute();
            header('location:index.php?succ=5');
            exit();
        }
    }

    // Méthode pour s'abonner ou se désabonner d'un utilisateur
    public static function subscribe($id_publisher, $id)
    {
        $bdo = ConnectionFactory::makeConnection();

        // Requête pour vérifier si l'utilisateur est déjà abonné
        $sql = "SELECT * FROM SUBSRIBE WHERE subsriber=? AND publisher=?";
        $resultSet = $bdo->prepare($sql);
        $resultSet->bindParam(1, $id);
        $resultSet->bindParam(2, $id_publisher);
        $resultSet->execute();

        // Si l'utilisateur est déjà abonné, on le désabonne
        if ($resultSet->fetch()) {
            $sql = "DELETE FROM SUBSRIBE WHERE publisher=? AND subsriber=?";
            $resultSet2 = $bdo->prepare($sql);
            $resultSet2->bindParam(1, $id_publisher);
            $resultSet2->bindParam(2, $id);
            $resultSet2->execute();
            header("location:index.php?action=page-user&iduser=" . $id_publisher . "&succ=1");
        } else {
            // Sinon, on l'abonne à l'utilisateur
            $sql = "INSERT INTO SUBSRIBE VALUES (?, ?)";
            $resultSet2 = $bdo->prepare($sql);
            $resultSet2->bindParam(1, $id_publisher);
            $resultSet2->bindParam(2, $id);
            $resultSet2->execute();
            header("location:index.php?action=page-user&iduser=" . $id_publisher . "&succ=2");
        }
    }
}
