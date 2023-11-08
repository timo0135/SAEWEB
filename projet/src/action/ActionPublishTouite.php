<?php

namespace iutnc\deefy\action;

use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\manip\ManipTouite;
use iutnc\deefy\touite\Touite;
use iutnc\deefy\user\User;


class ActionPublishTouite extends Action
{

    public function execute(): string
    {
        session_start();
        if ($this->http_method==="GET"){
            $res= "<form method='post' name='Ajouter Touitte'>
                <p>Contenu De Votre Touitte</p>
                <input type='text' name='message' maxlength='235' value='' required><br>
                <p>Chemin de votre image(optionnel)</p>
                <input type='text' name='image' value=''><br>
                <p>Description de votre image</p> 
                <input type='text' name='description' value=''><br>
                <input type='submit' name='envoyer' value='Touitter'><br>
</form>";
        }else if ($this->http_method==="POST"){
            ManipTouite::add_touite();
            $res="<p>Votre touite a était publie <a href='../../index.php'>retour au menu</a></p>";

        }
        return $res;
    }
}