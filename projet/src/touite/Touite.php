<?php

namespace iutnc\deefy\touite;

use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\user\User;
class Touite{
    protected User $user;
    protected string $message;
    protected string $date;

    protected ?int $answer = null;
    protected ?string $imagePath = null;
    protected ?string $descriptionImage = null;

    public function __construct(User $user, string $texte, string $date, int $answer = null){
        $this -> user = $user;
        $this -> message = $texte;
        $this -> date = $date;
        $this -> answer = $answer;
    }

    public function setImage(string $imagePath, string $descriptionImage){
        $this -> imagePath = $imagePath;
        $this -> descriptionImage = $descriptionImage;
    }

    public function getUser() : User{
        return $this -> user;
    }

    public function getMessage() : string{
        return $this -> message;
    }

    public function getDate() : string{
        return $this -> date;
    }

    public function addTouite(){
        ConnectionFactory::setConfig("db.config.ini");
        $bdd = ConnectionFactory::makeConnection();
        $sql = "insert into touite (message, date, id_user, answer) values (?, ?, ?, ?);";
        $resultset = $bdd->prepare($sql);
        $resultset->bindParam(1, $this->message);
        $resultset->bindParam(2, $this->date);
        $idUser = $this->user->getIdUser();
        $resultset->bindParam(3, $idUser);
        $resultset->bindParam(4, $this->answer);
        $resultset->execute();
    }

}