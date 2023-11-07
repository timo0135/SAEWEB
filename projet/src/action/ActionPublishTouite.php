<?php

namespace iutnc\deefy\action;

use iutnc\deefy\db\ConnectionFactory;
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
            $answer=null;
            if(isset($_SESSION['id_touitte'])){
                $answer=$_SESSION['id_touitte'];
            }
            $bdd=ConnectionFactory::makeConnection();
            $sql = "insert into touite (message, date, id_user, answer, path, description) values (?, now(), ?, ?, ?, ?);";
            $resultset = $bdd->prepare($sql);
            $resultset->bindParam(1, $_POST['message']);
            $idUser = $_SESSION['id'];
            $resultset->bindParam(3, $idUser);
            $resultset->bindParam(4, $answer);
            $resultset->bindParam(5, $_POST['image']);
            $resultset->bindParam(6, $_POST['description']);
            if($resultset->execute()){
                $res="Votre Touitte a bien était posté";
            }else{
                $res="Votre Touitte n'a pas pu être posté";
            }
        }
        return $res;
    }
}