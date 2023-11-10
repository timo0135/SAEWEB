<?php

namespace iutnc\touiter\action;
class ActionAfficherSettings extends Action{
    public function execute():string{
        $res="";
        if(isset($_GET['err'])){
            switch($_GET['err']){
                case '1':
                    $res.="
        <div class='err'>L'email est déjà utilisé</div>
                    ";
                    break;
                case '2':
                    $res.="
        <div class='err'>Mauvais mot de passe</div>
                    ";
                    break;
                case '3':
                    $res.="
        <div class='err'>Une erreur est survenue</div>
                    ";
                    break;
            }
            $res.="<br>";
        }elseif(isset($_GET['succ'])){
            switch($_GET['succ']){
                case '1':
                    $res.="
        <div class='succ'>L'email à bien été mis à jour</div>
                    ";
                    break;
                case '2':
                    $res.="
        <div class='succ'>Le mot de passe à bien été modifié</div>
                    ";
                    break;
            }
            $res.="<br>";

        }
        $res.="
    <div class='sub-title'>Changer d'email</div>
    <form action='index.php?action=settings' class='form-fit' method='POST'>
        Nouvel email
        <input type='text' name='nemail'><br>
        <br>
        <button>Mettre à jour</button>
    </form><br>
    <br>
    <br>
    <div class='sub-title'>Changer de mot de passe</div>
    <form action='index.php?action=settings' class='form-fit' method='POST'>
        Ancien mot de passe
        <input type='password' name='apassword'><br>
        Nouveau mot de passe
        <input type='password' name='npassword'><br>
        Confirmation du mot de passe
        <input type='password' name='cpassword'><br>
        <br>
        <button>Mettre à jour</button>
    </form>
        
        ";
    

        return $res;
    }
}
