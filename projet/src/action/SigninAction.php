<?php
namespace iutnc\touiter\action;
class SigninAction extends Action{

    public function execute():string{
        $res="";
//on recupère les erreur
        if(isset($_GET['err'])){
            switch($_GET['err']){
                //erreur 1 l'utilisateur n'a pas remplit le formulaire
                case '1':
                    $res.="
        <div class='err'>Le formulaire n'a pas été remplit</div>
                    ";
                    break;
                    //erreur 2 l'utilisateur n'a pas remplit correctement le formulaire
                case '2':
                    $res.="
        <div class='err'>Des informations sont manquantes</div>
                    ";
                    break;
                    //erreur 3 l'utilisateur a envoyé des information incorect
                case '3':
                    $res.="
        <div class='err'>Des informations sont incorrectes</div>
                    ";
                    break;
            }
        }
    //formulaire pour se connecter
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