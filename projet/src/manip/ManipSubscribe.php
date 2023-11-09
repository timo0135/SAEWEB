<?php

namespace iutnc\deefy\manip;

use iutnc\deefy\db\ConnectionFactory;

class ManipSubscribe
{
    public static function subscribeTag($id_tag,$id){
        $sql="select * from user2tag where id_tag=? and id_user=?";
        $bdd=ConnectionFactory::makeConnection();
        $resultSet=$bdd->prepare($sql);
        $resultSet->bindParam(1,$_GET['id_tag']);
        $resultSet->bindParam(2,$_SESSION['id']);
        $resultSet->execute();
        if($resultSet->fetch()){
            $sql="DELETE from user2tag where id_tag=? and id_user=?";
            $resultSet=$bdd->prepare($sql);
            $resultSet->bindParam(1,$_GET['id_tag']);
            $resultSet->bindParam(2,$_SESSION['id']);
            $resultSet->execute();
            header('location:index.php?succ=4');
            exit();
        }else {
            $sql = "INSERT into user2tag values (?,?)";
            $resultSet = $bdd->prepare($sql);
            $resultSet->bindParam(1, $_SESSION['id']);
            $resultSet->bindParam(2, $_GET['id_tag']);
            $resultSet->execute();
            header('location:index.php?succ=5');
            exit();
        }
    }


}