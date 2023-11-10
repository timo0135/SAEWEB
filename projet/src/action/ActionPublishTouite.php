<?php

namespace iutnc\touiter\action;

use iutnc\touiter\db\ConnectionFactory;
use iutnc\touiter\manip\ManipTouite;
use iutnc\touiter\touite\Touite;
use iutnc\touiter\user\User;


class ActionPublishTouite extends Action
{

    public function execute(): string
    {
        //Si la methode est get On demande à l'utilisateur les information pour publier son touitte
        if ($this->http_method==="GET"){
            $res= "
            <fieldset class='form-fit'><legend><h1>Publier un touite</h1></legend>
                <form method='post' name='Ajouter Touitte' enctype='multipart/form-data'>
                <p>Contenu du touite:</p>
                <input class='texteArea' type='text' name='message' maxlength='235' value='' required><br>
                <p>Selectionner une image (si vous voulez):</p>
                <input type='file' name='image'><br>
                <p>Description de l'image:</p> 
                <input type='text' name='description' value=''><br>
                <input class='envoyer' type='submit' name='envoyer' value='Touitter'><br>
                </form>
             </fieldset>";
        }else if ($this->http_method==="POST"){//sinon on doit psoter le touitte sur la base de donée
           try {
               //on utilise la methode add_touite de Maniptouite pour uploader le touite dans la base de donnée
                ManipTouite::add_touite();
                //si tout se passe bien on redirige l'utilisateur vers le menu
                header('location:index.php');
          }catch (\Exception $e){
              //si la methode add_touite de ManipTouite renvoie une exception (qui est forcément lié au fichier que l'utilisateur essaye d'uploader
               //on lui renvoie le formulaire en lui signalant l'erreur
                $res= "
                <fieldset class='form-fit'><legend><h1>Publier un touite</h1></legend>
                <form method='post' name='Ajouter Touitte' enctype='multipart/form-data'>
                <p>Contenu De Votre Touitte</p>
                <input type='text' name='message' maxlength='235' value='' required><br>
                <p>Chemin de votre image(optionnel)</p>
                <input type='file' name='image'><p>".$e->getMessage()."</p><br>
                <p>Description de votre image</p> 
                <input type='text' name='description' value=''><br>
                <input type='submit' name='envoyer' value='Touitter'><br>
                </form>
            </fieldset>
";
                return $res;
            }
        }

        return $res;
    }
}