<?php

namespace iutnc\touiter\action;

class AddUserAction extends Action{
    public function execute():string{
            $res = '';
            if(isset($_GET['err'])){
                //on récupere l'entete de l'erreur
                switch($_GET['err']){
                    //erreur 1 le formulaire n'a aps été remplit
                    case '1':
                        $res.="
            <div class='err'>Le formulaire n'a pas été remplit</div>
                        ";
                        break;
                        //erreur 2 l'utilisateur n'a pas entré toute les informations nécessaire
                    case '2':
                        $res.="
            <div class='err'>Des informations sont manquantes</div>
                        ";
                        break;
                        //erreur 3 l'utilisateur n'a pas entré deux fois le même mot de passe
                    case '3':
                        $res.="
            <div class='err'>Les mots de passes ne sont pas les mêmes</div>
                        ";
                        break;
                        //erreur 4 l'email est deja présent dans la base de donnée
                    case '4':
                        $res.="
            <div class='err'>L'email est déjà utilisé</div>
                        ";
                        break;
                        //erreur 5 erreur quelconque
                    case '5':
                        $res.="
            <div class='err'>Erreur d'insertion</div>
                        ";
                        break;
                }
            }
//formulaire d'insertion à touiter
            $res.= "
                <div class='form-fit'>
                    <div class='sub-title'>S'INSCRIRE</div>
                    <form action='index.php?action=inscription' method='POST'>
                        <label for='email' class='label'>Email</label><br>
                        <input id='email' class='input' type='email' name='email'><br>
                        <label for='nom' class='label'>Nom</label><br>
                        <input id='nom' class='input' type='text' name='nom'><br>
                        <label for='prenom' class='label'>Prénom</label><br>
                        <input id='prenom' class='input' type='text' name='prenom'><br>
                        <label for='mdp' class='label'>Mot de passe</label><br>
                        <input id='mdp' class='input' type='password' name='mot_de_passe'><br>
                        <label for='confmdp' class='label'>Confirmation du mot de passe</label><br>
                        <input id='confmdp' class='input' type='password' name='mot_de_passe_conf'><br><br>
                        <button> s'inscrire</button>
                    </form>
                </div>
            ";
            return $res;
    }
}
