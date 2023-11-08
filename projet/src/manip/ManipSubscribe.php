<?php

namespace iutnc\deefy\manip;

use iutnc\deefy\db\ConnectionFactory;

class ManipSubscribe
{

    public static function subscribe(){
        $bdo=ConnectionFactory::makeConnection();
        $sql="SELECT publisher from subscribe where subscriber=".$_SESSION['id'];
        if($resultSet=$bdo->query($sql)){
            $sql="DELETE FROM SUBSCRIBE WHERE publisher=? and subrsciber=?";
            $resultSet2=$bdo->prepare($sql);
            $resultSet2->bindParam(1,$_GET['id']);
            $resultSet2->bindParam(2,$_SESSION['id']);
            $resultSet2->execute();

        }else{
            $sql="INSERT INTO SUBSCRIBE VALUES(?,?)";
            $resultSet2=$bdo->prepare($sql);
            $resultSet2->bindParam(1,$_GET['id'],$_SESSION['id']);
            $resultSet2->execute();

        }

}
}