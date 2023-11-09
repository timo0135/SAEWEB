<?php

namespace iutnc\deefy\manip;

use iutnc\deefy\action\Action;
use iutnc\deefy\db\ConnectionFactory;

class ActionSubscribe extends Action
{

    public function execute(): string{
        $bdo=ConnectionFactory::makeConnection();
        $sql="SELECT publisher from SUBSRIBE where subsriber=".$_SESSION['id'];
        if($bdo->query($sql)){
            $sql="DELETE FROM SUBSRIBE WHERE publisher=? and subsriber=?";
            $resultSet2=$bdo->prepare($sql);
            $resultSet2->bindParam(1,$_GET['iduser']);
            $resultSet2->bindParam(2,$_SESSION['id']);
            $resultSet2->execute();


        }else{
            $sql="INSERT INTO SUBSCRIBE VALUES(?,?)";
            $resultSet2=$bdo->prepare($sql);
            $resultSet2->bindParam(1,$_GET['iduser'],$_SESSION['id']);
            $resultSet2->execute();
        }
        header('location:?action=page-user');
        exit;
    }
}