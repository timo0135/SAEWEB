<?php

namespace iutnc\deefy\user;

use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\touite\Touite;

class User
{
    private int $id_user,$role;
    private string $email,$firstname,$lastname,$pwd;



    public function __construct(int $id,int $role,string $email,string $firstname,string $lastname, string $pwd){
        $this->id_user=$id;
        $this->role=$role;
        $this->email=$email;
        $this->firstname=$firstname;
        $this->lastname=$lastname;
        $this->pwd=$pwd;
    }

    public function getIdUser(): int
    {
        return $this->id_user;
    }

    public function getRole(): int
    {
        return $this->role;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getPwd(): string
    {
        return $this->pwd;
    }

    public function recupTouiteUser():array{
        $touite=array();
        $connexion=ConnectionFactory::makeConnection();
        $sql="SELECT * from touite where id_user=?";
        $resultSet=$connexion->prepare($sql);
        $resultSet->bindParam(1,$this->id_user);
        $resultSet->execute();
        while ($row=$resultSet->fetch()){
            $touite[]=new Touite($row['id_touite'],$this,$row['message'],$row['date'],$row['answer'],$row['path'],$row['description']);
        }

        return $touite;
    }


    public function abonnees():array{
        $suiveur=array();
        $bdd=ConnectionFactory::makeConnection();
        $sql = "select id_user, role, email, firstname, lastname, password from user inner join subsribe on user.id_user = subsribe.subscriber where subsribe.publisher = ?;";
        $resultset = $bdd->prepare($sql);
        $idUser = $this->getIdUser();
        $resultset->bindParam(1, $idUser);
        $resultset->execute();
        while ($row = $resultset->fetch(PDO::FETCH_ASSOC)) {
            $suiveur[] = new User($row['id_user'], $row['role'], $row['email'], $row['firstname'], $row['lastname'], $row['password']);
        }
        return $suiveur;
    }
}