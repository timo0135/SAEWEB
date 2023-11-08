<?php

namespace iutnc\deefy\manip;

use iutnc\deefy\action\Action;
use iutnc\deefy\db\ConnectionFactory;

class ManipLike
{
    public function execute(): void
    {
        $bdd = ConnectionFactory::makeConnection();
        $sql = "select * from like where id_touite=? and id_user=?";
        $resultSet = $bdd->prepare($sql);
        $resultSet->bindParam(1,$_GET['id']);
        $resultSet->bindParam(2,$_SESSION['id']);
        $resultSet->execute();
        if ($resultSet->fetch()) {
            $sql = "update like set like=1";
            $resultSet = $bdd->prepare($sql);
            $resultSet->execute();
        } else {
            $sql = "INSERT INTO like VALUES(?,?,1)";
            $resultSet->bindParam(1, $_SESSION['id']);
            $resultSet->bindParam(2, $_GET['id']);
            $resultSet = $bdd->prepare($sql);
            $resultSet->execute();
        }
    }
}