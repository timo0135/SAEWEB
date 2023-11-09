<?php

namespace iutnc\deefy\action;

use iutnc\deefy\db\ConnectionFactory;
class ActionProfilUser extends Action{

    public function execute(): string{
        $res="";
        if(isset($_GET['iduser'])){
            $sql="SELECT * from user where id_user=?;";
            $bdd=ConnectionFactory::makeConnection();
            $resultSet=$bdd->prepare($sql);
            $resultSet->bindParam(1,$_GET['iduser']);
            $resultSet->execute();
            $res.="<div class='form-fit'>";
            while ($row=$resultSet->fetch()){
                $res .= "<div class='user'>";
                $res .= "<div class='user-name'>".$row['firstname']." ".$row['lastname']."</div>";
                $res .= "<div class='user-email'>".$row['email']."</div>";
                $res .= "</div>";
            }
            $res.="</div>";
            $sql="select * from touite where id_user =?;";
            $resultSet=$bdd->prepare($sql);
            $resultSet->bindParam(1,$_GET['iduser']);
            $resultSet->execute();
            while ($row=$resultSet->fetch()){
                $sql2="select firstname, lastname from user where id_user =?;";
                $result=$bdd->prepare($sql2);
                $result->bindParam(1,$_GET['iduser']);
                $result->execute();
                $rw = $result->fetch();
                $res.=
                    "<fieldset class='touite-box'>
                <legend><a href='?action=page-user&iduser=".$row['id_user']."'><h2>&nbsp&nbsp".$rw['firstname']." ".$rw['lastname']."</h2></a></legend><p>".$row['message']."</p><br>";
                if(!is_null($row['path'])){
                    $res.="<img src=".$row['path']." alt=".$row['description']."><br>";
                }

                $res.="<a href=index.php?action=voirPlus&id=".$row['id_touite']." class='voirplus'>Voir plus</a></fieldset><br>";
            }
        }
        return $res;
    }
}