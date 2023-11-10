<?php

namespace iutnc\deefy\action;

use iutnc\deefy\db\ConnectionFactory;

class ActionTouiteTagSub extends Action
{
    private $listTouite;
    private $resultSet;

    public function execute():string{
        $bdd=ConnectionFactory::makeConnection();
        $this->listTouite = "select distinct touite.* from touite inner join subsribe on subsribe.publisher = touite.id_user where subsribe.subsriber = ? union select distinct touite.* from touite inner join touite2tag on touite.id_touite = touite2tag.id_touite inner join user2tag on touite2tag.id_tag = user2tag.id_tag where user2tag.id_user = ? order by date desc;";
        $this->resultSet = $bdd->prepare($this->listTouite);
        $this->resultSet->bindParam(1, $_SESSION['id']);
        $this->resultSet->bindParam(2, $_SESSION['id']);
        $this->resultSet->execute();
        $affichage="";
        while ($row=$this->resultSet->fetch()){
            $user="select firstname, lastname from user where id_user=?";
            $res=$bdd->prepare($user);
            $res->bindParam(1, $row['id_user']);
            $res->execute();
            $us=$res->fetch();
            $affichage=$affichage."<fieldset class='touite-box'><legend><a href='?action=page-user&iduser=".$row['id_user']."'><h2 class='proprioTouite'>".$us['firstname']." ".$us['lastname']."</h2></a></legend><p class='messageTouite'>".$row['message']."</p><br>";
            if(!empty($row['answer'])){
                $commande = "SELECT User.id_user,firstname,lastname FROM User 
                JOIN touite ON touite.id_user=user.id_user 
                WHERE id_touite=".$row['answer'];
                $res = $bdd -> query($commande);
                $row2 = $res->fetch();
                $affichage.= "
                <p class='top-right'>
                    reponse à 
                    <a href='?action=voirPlus&id=".$row['answer']."'>
                        &nbsp".$row2['firstname']."&nbsp".$row2['lastname']."
                    </a>
                </p>";
            }
            if(!is_null($row['path'])){
                $affichage=$affichage."<img class='imagetouite' src=".$row['path']." alt=".$row['description']."><br>";
            }
            $affichage=$affichage."<a  href=index.php?action=voirPlus&id=".$row['id_touite']." class='voirplus'>Voir plus</a></fieldset><br>";

        }
        return $affichage;
    }
}