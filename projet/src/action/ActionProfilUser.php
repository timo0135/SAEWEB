<?php

namespace iutnc\deefy\action;

use iutnc\deefy\db\ConnectionFactory;
class ActionProfilUser extends Action{

    public function execute(): string{
        $res="";
        if(isset($_GET['succ'])){
            switch($_GET['succ']){
                case '1':
                    $res.="
            <div class='succ'>Vous vous etes bien desabonné</div>
                    ";
                    break;
                case '2':
                    $res.="
            <div class='succ'>Vous vous etes bien abonné</div>
                    ";
                    break;
                }
        }
        if(isset($_GET['iduser']) && ((isset($_SESSION['id'])&&$_GET['iduser']!=$_SESSION['id'])||!isset($_SESSION['id']))){
            $sql="SELECT * from user where id_user=?;";
            $requete = "select publisher from SUBSRIBE where subsriber = ?;";
            $bdd=ConnectionFactory::makeConnection();
            $resultSet=$bdd->prepare($sql);
            $resultSet->bindParam(1,$_GET['iduser']);
            $resultSet->execute();
            $res.="<div class='haut-page'>";
            $row=$resultSet->fetch();
            $res .= "<div class='user'>";
            $res .= "<div class='user-name'>".$row['firstname']." ".$row['lastname']."</div>";
            $res .= "<div class='user-email'>".$row['email']."</div>";
            $resultSet2=$bdd->prepare($requete);
            $resultSet2->bindParam(1,$_SESSION['id']);
            $resultSet2->execute();
            $res2="";
            if ($rw = $resultSet2->fetch()) {
                do {

                    if($rw['publisher']==$_GET['iduser']){
                        $res2 = "<a href='index.php?action=subscribe&iduser=".$_GET['iduser']."'><button class='abonnement'>Se désabonner </button></a>";
                        break;
                    }else{
                        $res2 = "<a href='index.php?action=subscribe&iduser=".$_GET['iduser']."'><button class='abonnement'>S'abonner </button></a>";
                    }
                } while ($rw = $resultSet2->fetch());

            } else {
                $res2 = "<a href='index.php?action=subscribe&iduser=".$_GET['iduser']."'><button class='abonnement'>S'abonner </button></a>";
            }
            $res .= $res2;
            $res .= "</div>";

            $res.="</div>";
            $res.="<h1 class='rep'>Touites: </h1>";
            $sql="select distinct touite.* from touite inner join subsribe on subsribe.publisher = touite.id_user where subsribe.subsriber = ? union select distinct touite.* from touite inner join touite2tag on touite.id_touite = touite2tag.id_touite inner join user2tag on touite2tag.id_tag = user2tag.id_tag where user2tag.id_user = ? order by date desc;";
            $resultSet=$bdd->prepare($sql);
            $resultSet->bindParam(1,$_GET['iduser']);
            $resultSet->bindParam(2,$_GET['iduser']);
            $resultSet->execute();
            while ($row=$resultSet->fetch()){
                $sql2="select firstname, lastname from user where id_user =?;";
                $result=$bdd->prepare($sql2);
                $result->bindParam(1,$row['id_user']);
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
        }else{
            $sql="SELECT * from user where id_user=?;";
            $bdd=ConnectionFactory::makeConnection();
            $resultSet=$bdd->prepare($sql);
            $resultSet->bindParam(1,$_SESSION['id']);
            $resultSet->execute();
            $res.="<div class='haut-page'>";
            $row=$resultSet->fetch();
            $res .= "<div class='user'>";
            $res .= "<div class='user-name'>".$row['firstname']." ".$row['lastname']."</div>";
            $res .= "<div class='user-email'>".$row['email']."</div>";
            $res .= "</div>";

            $res.="</div>";
            $res.="<h1 class='rep'>Touites: </h1>";
            $sql="select distinct touite.* from touite inner join subsribe on subsribe.publisher = touite.id_user where subsribe.subsriber = ? union select distinct touite.* from touite inner join touite2tag on touite.id_touite = touite2tag.id_touite inner join user2tag on touite2tag.id_tag = user2tag.id_tag where user2tag.id_user = ? order by date desc;";
            $resultSet=$bdd->prepare($sql);
            $resultSet->bindParam(1,$_SESSION['id']);
            $resultSet->bindParam(2,$_SESSION['id']);
            $resultSet->execute();
            while ($row=$resultSet->fetch()){
                $sql2="select firstname, lastname from user where id_user =?;";
                $result=$bdd->prepare($sql2);
                $result->bindParam(1,$row['id_user']);
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