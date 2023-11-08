<?php

namespace iutnc\deefy\touite;

use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\user\User;
class Touite{
    protected int $id_touite;
    protected User $user;
    protected string $message;
    protected string $date;

    protected ?int $answer = null;
    protected ?string $imagePath = null;
    protected ?string $descriptionImage = null;

    public function __construct(int $id,User $user, string $texte, string $date, int $answer = null, string $imagePath = null, string $descriptionImage = null){
        $this->id_touite=$id;
        $this -> user = $user;
        $this -> message = $texte;
        $this -> date = $date;
        $this -> answer = $answer;
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
    public function getID():int{
        return $this->id_touite;
    }

    public function supprimerTouite(){
        $connexion=ConnectionFactory::makeConnection();
        $sql="DELETE from touite where id_touite=".$this->id_touite;
        $connexion->exec($sql);
    }

}