<?php

namespace iutnc\deefy\user;

class User
{
    private int $id_user,$role;
    private string $email,$firstname,$lastname,$pwd;


    private function __construct(int $id,int $role,string $email,string $firstname,string $lastname, string $pwd){
        $this->id_user=$id;
        $this->role=$role;
        $this->email=$email;
        $this->firstname=$firstname;
        $this->lastname=$lastname;
        $this->pwd=$pwd;
    }

}