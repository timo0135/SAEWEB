<?php

namespace iutnc\touiter\manip;

use iutnc\touiter\db\ConnectionFactory;

class ManipSupTouite
{
    public function execute(): void
    {
        $bdd = ConnectionFactory::makeConnection();

        // On récupère le rôle de l'utilisateur actuel
        $userRoleQuery = "SELECT role FROM USER WHERE id_user=?";
        $userRoleStatement = $bdd->prepare($userRoleQuery);
        $userRoleStatement->bindParam(1, $_SESSION['id']);
        $userRoleStatement->execute();
        $userResult = $userRoleStatement->fetch();

        // On récupère l'id de l'utilisateur associé au touite que l'on veut supprimer
        $touiteUserIdQuery = "SELECT id_user FROM TOUITE WHERE id_touite=?";
        $touiteUserIdStatement = $bdd->prepare($touiteUserIdQuery);
        $touiteUserIdStatement->bindParam(1, $_GET['id']);
        $touiteUserIdStatement->execute();
        $touiteUserResult = $touiteUserIdStatement->fetch();

        // Vérifie si l'utilisateur actuel est le propriétaire du touite ou s'il a le droit de supprimer le touite (admin)
        if (($_SESSION['id'] == $touiteUserResult['id_user']) || ($userResult['role'] == 100)) {
            $id_touite = $_GET['id'];

            // Supprime les associations entre le touite et les tags
            $sql = "DELETE FROM TOUITE2TAG WHERE id_touite=:id_touite";
            $resultSet = $bdd->prepare($sql);
            $resultSet->bindParam(':id_touite', $id_touite);
            $resultSet->execute();

            // Supprime les likes associés au touite
            $sql = "DELETE FROM `LIKE` WHERE id_touite=:id_touite";
            $resultSet = $bdd->prepare($sql);
            $resultSet->bindParam(':id_touite', $id_touite);
            $resultSet->execute();

            // Supprime les réponses associées au touite
            $sql = "DELETE FROM TOUITE WHERE answer=:id_touite";
            $resultSet = $bdd->prepare($sql);
            $resultSet->bindParam(':id_touite', $id_touite);
            $resultSet->execute();

            // Supprime le touite lui-même
            $sql = "DELETE FROM TOUITE WHERE id_touite=:id_touite";
            $resultSet = $bdd->prepare($sql);
            $resultSet->bindParam(':id_touite', $id_touite);
            $resultSet->execute();
        }
    }
}
