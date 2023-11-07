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

            $res.= ";
                <div class='title'>TWEETER</div><br>
                <br><br>
                <div class='form-fit'>
                    <div class='sub-title'>S'INSCRIRE</div>
                    <form action='index.php?action=inscription' method='POST'>
                        <p>EMAIL</p>
                        <input type='email' name='email'><br>
                        <p>NOM</p>
                        <input type='text' name='nom'><br>
                        <p>PRENOM</p>
                        <input type='text' name='prenom'><br>
                        <p>MOT DE PASSE</p>
                        <input type='password' name='mot_de_passe'><br>
                        <p>CONFIRMATION MOT DE PASSE</p>
                        <input type='password' name='mot_de_passe_conf'><br><br>
                        <button> s'inscrire</button>
                    </form>
                </div>
            ";
            return $res;
    }
}
