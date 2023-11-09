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
                <p>Contenu De Votre Touitte</p>
                <input type='text' name='message' maxlength='235' value='' required><br>
                <p>Chemin de votre image(optionnel)</p>
                <input type='file' name='image'><br>
                <p>Description de votre image</p> 
                <input type='text' name='description' value=''><br>
                <input type='submit' name='envoyer' value='Touitter'><br>
            </div>
</form>";
        }else if ($this->http_method==="POST"){
            try {
                ManipTouite::add_touite();
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
            unset($_FILES['image']);
            $res="<p>Votre touite a était publie <a href='../../index.php'>retour au menu</a></p>";

        }
        return $res;
    }
}