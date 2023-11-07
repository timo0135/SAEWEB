<?php

namespace iutnc\deefy\renderer;

use iutnc\deefy\touite\Touite;
use iutnc\deefy\user\User;

class RendererUser{
    protected User $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    protected function render(): string
    {
        $touite=$this->user->recupTouiteUser();
        $res= "<h2>" . $this->user->getFirstname()." ". $this->user->getLastname(). "</h2>"
            . "<p>Email: " . $this->user->getEmail(). "</p>";
        foreach ($touite as $t) {
            $affichage = "<div></div><h2>" . $t->getUser()->getFirstName() . " " . $t->getUser()->getLastName() . "</h2><br><p>" . $t->getMessage() . "</p><br>";
        }
        return $affichage;
    }
}