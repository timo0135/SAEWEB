<?php
namespace iutnc\deefy\action;
class SigninAction extends Action{

    public function execute():string{
        $res="";

        if(isset($_GET['err'])){
            switch($_GET['err']){
                case '1':
                    $res.="
        <div class='err'>Le formulaire n'a pas été remplit</div>
                    ";
                    break;
                case '2':
                    $res.="
        <div class='err'>Des informations sont manquantes</div>
                    ";
                    break;
                case '3':
                    $res.="
        <div class='err'>Des informations sont incorrectes</div>
                    ";
                    break;
            }
        }
        
        $res.="
        <div class='form-fit'>
            <div class='sub-title'>SE CONNECTER</div>
            <form action='index.php?action=connexion' method='POST'>
                <p>EMAIL</p>
                <input type='email' name='email'><br>
                <p>MOT DE PASSE</p>
                <input type='password' name='mot_de_passe'><br><br>
                <button>se connecter</button>
            </form>
        </div>";
        return $res;
        
    }
}