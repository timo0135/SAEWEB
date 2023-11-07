<?php

namespace iutnc\deefy\touite;

use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\user\User;

class Commentaire extends Touite{
    private int $answer;

    public function __construct(User $user, string $texte, string $date, int $answer){
        parent::__construct($user, $texte, $date);
        $this->answer = $answer;
    }

    public function getAnswer() : string{
        return $this -> answer;
    }

    public function addTouite(){
        parent::addTouite();
        $bdd = ConnectionFactory::makeConnection();
        $sql = "insert into touite (message, date, id_user, answer) values (?,?,?,?);";
        $resultset = $bdd->prepare($sql);
        $resultset->bindParam(1, $this->message);
        $resultset->bindParam(2, $this->date);
        $idUser = $this->user->getIdUser();
        $resultset->bindParam(3, $idUser);
        $resultset->bindParam(4, $this->answer);
        $resultset->execute();
    }

}