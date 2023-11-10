<?php

namespace iutnc\deefy\action;

class ActionAfficherSettings extends Action{
    public function execute():string{
        $res="
    <div class='sub-title'>Changer d'email</div>
    <form action='' class='form-fit'>
        Nouvel email
        <input type='text' name='nemail'><br>
        <br>
        <button>Mettre à jour</button>
    </form><br>
    <br>
    <br>
    <div class='sub-title'>Changer de mot de passe</div>
    <form action='' class='form-fit'>
        Ancien mot de passe
        <input type='password' name='amdp'><br>
        Nouveau mot de passe
        <input type='password' name='nmdp'><br>
        Confirmation du mot de passe
        <input type='password' name='cmdp'><br>
        <br>
        <button>Mettre à jour</button>
    </form>
        
        ";
    

        return $res;
    }
}
