<?php

namespace iutnc\touiter\action;

class ActionAfficherSettings extends Action
{
    public function execute(): string
    {
        // Chaîne de résultat à retourner
        $res = "";

        // Vérifie s'il y a une erreur dans la requête GET
        if (isset($_GET['err'])) {
            // On gère les différents types d'erreurs
            switch ($_GET['err']) {
                case '1':
                    $res .= "<div class='err'>L'email est déjà utilisé</div>";
                    break;
                case '2':
                    $res .= "<div class='err'>Mauvais mot de passe</div>";
                    break;
                case '3':
                    $res .= "<div class='err'>Une erreur est survenue</div>";
                    break;
            }
            $res .= "<br>";
        }
        // Vérifie s'il y a un succès dans la requête GET
        elseif (isset($_GET['succ'])) {
            // On gère les différents types de succès
            switch ($_GET['succ']) {
                case '1':
                    $res .= "<div class='succ'>L'email a bien été mis à jour</div>";
                    break;
                case '2':
                    $res .= "<div class='succ'>Le mot de passe a bien été modifié</div>";
                    break;
            }
            $res .= "<br>";
        }

        // Formulaire pour changer l'email
        $res .= "
        <div class='sub-title'>Changer d'email</div>
        <form action='index.php?action=settings' class='form-fit' method='POST'>
            Nouvel email
            <input type='text' name='nemail'><br>
            <br>
            <button>Mettre à jour</button>
        </form><br>
        <br>
        <br>";

        // Formulaire pour changer le mot de passe
        $res .= "
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
        </form>";

        // Retourne le résultat HTML
        return $res;
    }
}
