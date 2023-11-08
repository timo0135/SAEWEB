<?php

namespace iutnc\deefy\manip;

use iutnc\deefy\action\Action;
use iutnc\deefy\db\ConnectionFactory;

class ManipDislike
{
    public function execute(): void
    {
        $bdd = ConnectionFactory::makeConnection();
        $sql = "select * from like where id_touite=" . $_GET['id'] . " and id_user=" . $_SESSION['id'];
        $resultSet = $bdd->prepare($sql);
        $resultSet->execute();
        if ($resultSet->fetch()) {
            $sql = "update like set like=0";
            $resultSet = $bdd->prepare($sql);
            $resultSet->execute();
        } else {
            $sql = "INSERT INTO like VALUES(?,?,0)";
            $resultSet->bindParam(1, $_SESSION['id']);
            $resultSet->bindParam(2, $_GET['id']);
            $resultSet = $bdd->prepare($sql);
            $resultSet->execute();
        }
    }

}