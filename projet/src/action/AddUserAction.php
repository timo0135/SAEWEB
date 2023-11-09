<?php

namespace iutnc\deefy\action;

class AddUserAction extends Action{
    public function execute():string{
            $res = '';
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
            <div class='err'>Les mots de passes ne sont pas les mêmes</div>
                        ";
                        break;
                    case '4':
                        $res.="
            <div class='err'>L'email est déjà utilisé</div>
                        ";
                        break;
                    case '5':
                        $res.="
            <div class='err'>Erreur d'insertion</div>
                        ";
                        break;
                }
            }

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
