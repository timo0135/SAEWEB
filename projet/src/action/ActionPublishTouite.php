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
        if ($this->http_method==="GET"){
            $res= "
            <div class='form-fit'>
                <form method='post' name='Ajouter Touitte' enctype='multipart/form-data'>
                <p>Contenu du touite:</p>
                <input class='texteArea' type='text' name='message' maxlength='235' value='' required><br>
                <p>Selectionner une image (si vous voulez):</p>
                <input type='file' name='image'><br>
                <p>Description de l'image:</p> 
                <input type='text' name='description' value=''><br>
                <input class='envoyer' type='submit' name='envoyer' value='Touitter'><br>
            </div>
</form>";
        }else if ($this->http_method==="POST"){
            try {
                ManipTouite::add_touite();
                header('location:index.php');
            }catch (\Exception $e){

                $res= "
            <div class='form-fit'>
                <form method='post' name='Ajouter Touitte' enctype='multipart/form-data'>
                <p>Contenu De Votre Touitte</p>
                <input type='text' name='message' maxlength='235' value='' required><br>
                <p>Chemin de votre image(optionnel)</p>
                <input type='file' name='image'><p>".$e->getMessage()."</p><br>
                <p>Description de votre image</p> 
                <input type='text' name='description' value=''><br>
                <input type='submit' name='envoyer' value='Touitter'><br>
            </div>
</form>";
                return $res;
            }
        }
        return $res;
    }
}