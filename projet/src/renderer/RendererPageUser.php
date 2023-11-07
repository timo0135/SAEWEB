<?php

namespace iutnc\deefy\renderer;

use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\user\User;
use PDO;

class RendererPageUser{
    protected $user;
    protected $suivis = array();

    public function __construct(User $user){
        $this->user = $user;
        ConnectionFactory::setConfig("db.config.ini");
        $bdd = ConnectionFactory::makeConnection();
        $sql = "select id_user, role, email, firstname, lastname, password from user inner join subsribe on user.id_user = subsribe.publisher where subsribe.subsriber = ?;";
        $resultset = $bdd->prepare($sql);
        $idUser = $this->user->getIdUser();
        $resultset->bindParam(1, $idUser);
        $resultset->execute();
        while ($row = $resultset->fetch(PDO::FETCH_ASSOC)) {
            $this->suivis[] = new User($row['id_user'], $row['role'], $row['email'], $row['firstname'], $row['lastname'], $row['password']);
        }
    }

    public function render() : string{
        $res = "";
        foreach ($this->suivis as $user){
            $res .= "<div class=\"user\">";
            $res .= "<div class=\"user-name\">".$user->getFirstname()." ".$user->getLastname()."</div>";
            $res .= "<div class=\"user-email\">".$user->getEmail()."</div>";
            $res .= "</div>";
        }
        return $res;
    }
}