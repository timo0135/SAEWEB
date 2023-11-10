<?php

namespace iutnc\touiter\action;

use iutnc\touiter\db\ConnectionFactory;

class ActionTouiteTagSub extends Action
{
    private $listTouite;
    private $resultSet;

    public function execute():string{
        $bdd=ConnectionFactory::makeConnection();
        //requete permet de recuperer les touite qui possedent un tag auquel un utilisateur est abonné et les touite envoyé par les utilisateur auquel il est abonné
        $this->listTouite = "select distinct TOUITE.* from TOUITE inner join SUBSRIBE on SUBSRIBE.publisher = TOUITE.id_user where SUBSRIBE.subsriber = ? union select distinct TOUITE.* from TOUITE inner join TOUITE2TAG on TOUITE.id_touite = TOUITE2TAG.id_touite inner join USER2TAG on TOUITE2TAG.id_tag = USER2TAG.id_tag where USER2TAG.id_user = ? order by date desc;";
        $this->resultSet = $bdd->prepare($this->listTouite);
        $this->resultSet->bindParam(1, $_SESSION['id']);
        $this->resultSet->bindParam(2, $_SESSION['id']);
        $this->resultSet->execute();
        $affichage="";
        while ($row=$this->resultSet->fetch()){
            //on selectione l'utilisateur qui a mis le touite
            $user="select firstname, lastname from USER where id_user=?";
            $res=$bdd->prepare($user);
            $res->bindParam(1, $row['id_user']);
            $res->execute();
            $us=$res->fetch();
            //on met un lien vers le profil de l'utilisateur et on l'affiche
            $affichage=$affichage."<fieldset class='touite-box'><legend><a href='?action=page-user&iduser=".$row['id_user']."'><h2 class='proprioTouite'>".$us['firstname']." ".$us['lastname']."</h2></a></legend><p class='messageTouite'>".$row['message']."</p><br>";
            if(!empty($row['answer'])){
                $commande = "SELECT USER.id_user,firstname,lastname FROM USER 
                JOIN TOUITE ON TOUITE.id_user=USER.id_user 
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
            $affichage=$affichage."<a  href=index.php?action=voirPlus&id=".$row['id_touite']." class='voirplus'><img src='icon/more.png' style='width:30px;margin:0;'></a></fieldset><br>";

        }
        return $affichage;
    }
}