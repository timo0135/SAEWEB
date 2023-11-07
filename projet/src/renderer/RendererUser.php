<?php

namespace iutnc\deefy\renderer;

use iutnc\deefy\user\User;

class RendererUser{
    protected User $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    protected function render(): string
    {
        return "<h2>" . $this->user->getFirstname()." ". $this->user->getLastname(). "</h2>"
            . "<p>Email: " . $this->user->getEmail(). "</p>";
    }



}