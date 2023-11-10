<?php

namespace iutnc\deefy\manip;

use iutnc\deefy\db\ConnectionFactory;

class ManipSubscribe
{
    public static function subscribeTag($id_tag,$id){
        $sql="select * from user2tag where id_tag=? and id_user=?";
        $bdd=ConnectionFactory::makeConnection();
        $resultSet=$bdd->prepare($sql);
        $resultSet->bindParam(1,$id_tag);
        $resultSet->bindParam(2,$id);
        $resultSet->execute();
        if($resultSet->fetch()){
            $sql="DELETE from user2tag where id_tag=? and id_user=?";
            $resultSet=$bdd->prepare($sql);
            $resultSet->bindParam(1,$id_tag);
            $resultSet->bindParam(2,$id);
            $resultSet->execute();
            header('location:index.php?succ=4');
            exit();
        }else {
            $sql = "INSERT into user2tag values (?,?)";
            $resultSet = $bdd->prepare($sql);
            $resultSet->bindParam(1, $id);
            $resultSet->bindParam(2, $id_tag);
            $resultSet->execute();
            header('location:index.php?succ=5');
            exit();
        }
    }

    public static function subscribe($id_publisher,$id){
        $bdo=ConnectionFactory::makeConnection();
        $sql="SELECT * from SUBSRIBE WHERE subsriber=".$id." AND publisher=".$id_publisher;
        $resultSet = $bdo->query($sql);
        if($resultSet->fetch()){
            $sql="DELETE FROM SUBSRIBE WHERE publisher=? and subsriber=?";
            $resultSet2=$bdo->prepare($sql);
            $resultSet2->bindParam(1,$id_publisher);
            $resultSet2->bindParam(2,$id);
            $resultSet2->execute();
            

        }else{
            $sql="INSERT INTO SUBSRIBE VALUES(?,?)";
            $resultSet2=$bdo->prepare($sql);
            $resultSet2->bindParam(1,$id_publisher);
            $resultSet2->bindParam(2,$id);
            $resultSet2->execute();
        }

    }

}