<?php

namespace iutnc\deefy\action;

use iutnc\deefy\db\ConnectionFactory;

class ActionDislike extends Action
{
    public function execute(): string
    {
        session_start();
        $res="";
        $bdd = ConnectionFactory::makeConnection();
        $sql = "select * from like where id_touite=" . $_GET['id'] . " and id_user=" . $_SESSION['id'];
        $resultSet = $bdd->prepare($sql);
        $resultSet->execute();
        if ($resultSet->fetch()) {
            $sql = "update like set like=false";
            $resultSet = $bdd->prepare($sql);
            $resultSet->execute();
        } else {
            $sql = "INSERT INTO like VALUES(?,?,false)";
            $resultSet->bindParam(1, $_SESSION['id']);
            $resultSet->bindParam(2, $_GET['id']);
            $resultSet = $bdd->prepare($sql);
            $resultSet->execute();
        }
        return $res;
    }

}