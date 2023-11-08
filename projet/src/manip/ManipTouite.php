<?php

namespace iutnc\deefy\manip;

use iutnc\deefy\db\ConnectionFactory;

class ManipTouite
{

    public static function add_touite():void{
        session_start();
        $answer=null;
        if(isset($_SESSION['id_touitte'])){
            $answer=$_SESSION['id_touitte'];
        }
        $message=$_POST['message'];
        $bdd=ConnectionFactory::makeConnection();
        $date=date('Y-m-d H:i:s');
        $sql = "insert into touite (message, date, id_user, answer, path, description) values (?, ?, ?, ?, ?, ?);";
        $resultset = $bdd->prepare($sql);
        $resultset->bindParam(1, $_POST['message']);
        $resultset->bindParam(2,$date);
        $idUser = $_SESSION['id'];
        $resultset->bindParam(3, $idUser);
        $resultset->bindParam(4, $answer);
        $resultset->bindParam(5, $_POST['image']);
        $resultset->bindParam(6, $_POST['description']);
        if($resultset->execute()){
            $res="Votre Touitte a bien était posté";
        }else{
            $res="Votre Touitte n'a pas pu être posté";
        }
        //on recupere l'id du touite pour inserer apres les eventuels tag qu'il contient
        $sql="SELECT id_touite from touite where id_user=? and date=?";
        $resultset=$bdd->prepare($sql);
        $resultset->bindParam(1,$_SESSION['id']);
        $resultset->bindParam(2,$date);
        $resultset->execute();
        $row=$resultset->fetch();
        $id_touite=$row['id_touite'];
        $tabPartieTouitte=explode(" ",$_POST['message']);
        //on parcourt tout les mots du tweet et on verifie si ce sont des tag (ils commentcnet par #) ou pas
        foreach ($tabPartieTouitte as $t){
            if(substr($t,0,1)==='#'){
                $bdo=ConnectionFactory::makeConnection();
                $sql="select label from tag where label=?";
                $resultSet=$bdo->prepare($sql);
                $resultSet->bindParam($t);
                if(!$resultSet->execute()){
                    $sql="INSERT INTO tag values (?,?)";
                    $description="Description pas encore fournie";
                    $resultSet=$bdo->prepare($sql);
                    $resultSet->bindParam(1,$t);
                    $resultSet->bindParam(2,$description);
                }
                $sql="select id_tag from tag where label=?";
                $resultSet=$bdo->prepare($sql);
                $resultSet->bindParam(1,$t);
                $resultSet->execute();
                $row=$resultSet->fetch();
                $id_tag=$row['id_tag'];
                $sql="SELECT * from touite2tag =?";
                $resultSet=$bdo->prepare($sql);
                $resultSet->bindParam(1,$id_tag);
                if (!$resultSet->execute()){
                    $sql="INSERT INTO touite2tag values (?,?)";
                    $resultSet2=$bdo->prepare($sql);
                    $resultSet2->bindParam(1,$id_tag);
                    $resultSet2->bindParam(2,$id_touite);
                    $resultSet2->execute();
                }
            }
        }
    }
    public static function deleteTouite():bool{
        $sql="DELETE FROM TOUITE where id_touite=?";
        $bdo=ConnectionFactory::makeConnection();
        $resultSet=$bdo->prepare($sql);
        $resultSet->bindParam(1,$_GET['id']);
        $resultSet->execute();
        return true;
    }
}