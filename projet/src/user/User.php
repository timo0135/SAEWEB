<?php

namespace iutnc\deefy\user;

class User
{
    private int $id_user,$role;
    private string $email,$firstname,$lastname;


    private function __consctruct(int $id,int $role,string $email,string $firstname,string $lastname){
        $this->id_user=$id;
        $this->role=$role;
        $this->email=$email;
        $this->firstname=$firstname;
        $this->lastname=$lastname;
    }

}