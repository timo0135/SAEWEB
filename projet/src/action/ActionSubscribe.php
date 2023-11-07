<?php

namespace iutnc\deefy\action;

use Cassandra\PreparedStatement;
use iutnc\deefy\db\ConnectionFactory;

class ActionSubscribe extends Action
{

    public function execute(): string
    {
        session_start();
        $bdo=ConnectionFactory::makeConnection();
        $sql="SELECT publisher from subscribe where subscriber=".$_SESSION['id'];
        if($resultSet=$bdo->query($sql)){
            $sql="DELETE FROM SUBSCRIBE WHERE publisher=? and subrsciber=?";
            $resultSet2=$bdo->prepare($sql);
            $resultSet2->bindParam(1,$_GET['id']);
            $resultSet2->bindParam(2,$_SESSION['id']);
            $resultSet2->execute();
            $res="Vous vous êtes bien desabonnées";
        }else{
        $sql="INSERT INTO SUBSCRIBE VALUES(?,?)";
        $resultSet2=$bdo->prepare($sql);
        $resultSet2->bindParam(1,$_GET['id'],$_SESSION['id']);
        $resultSet2->execute();
        $res="Vous vous êtes bien abonné";
        }
        return $res;
    }
}