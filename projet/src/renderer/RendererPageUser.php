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
        $this->suivis=$user->abonnees();
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