<?php
namespace iutnc\touiter\action;

use iutnc\touiter\action\ActionListTouite;

class ChoiceAction extends Action{
    public function execute():string{
            $res="";
            //on récupère l'entete de l'erreur
            if(isset($_GET['err'])){
                switch($_GET['err']){
                    //erreur 1 l'utilisateur essaie de se connecter alors qu'il est deja connecté
                    case '1':
                        $res.="
            <div class='err'>Vous etes déjà connecté</div>
                        ";
                        break;
                        //erreur 2 l'utilisateur essaie d'acceder à une resource où il doit être connecté pour y acceder alors qu'il n'est pas acceder
                    case '2':
                        $res.="
            <div class='err'>Vous devez etre connecté</div>
                        ";
                        break;
                        //erreur 3 erreuer quelconque
                    case '3':
                        $res.="
            <div class='err'>Une erreur est survenue</div>
                        ";
                        break;
                        //erreur 4 l'utilisateur essaaie d'acceder à une page laors qu'il n'en a pas l'acces
                    case '4':
                        $res.="
            <div class='err'>Vous n'avez pas les droit d'acceder à cette page</div>
                        ";
                        break;
                }
                $res.="<br>";
            }elseif(isset($_GET['succ'])){
                //on récupere l'entete des succes
                switch($_GET['succ']){
                    //succes 1 l'utilisateur c'est bien inscrit
                    case '1':
                        $res.="
            <div class='succ'>Vous etes maintenant inscrit</div>
                        ";
                        break;
                        //succes 2 l'utilisateur a réussi à se connecter
                    case '2':
                        $res.="
            <div class='succ'>Vous etes maintenant connecté</div>
                        ";
                        break;
                        //succes 3 l'utilisateur a bien réussi à se deconnecter
                    case '3':
                        $res.="
            <div class='succ'>vous etes maintenant déconnecté</div>
                        ";
                        break;
                        //succes 4 l'utilisateur c'est bien désabonné
                    case '4':
                        $res.="
            <div class='succ'>vous etes maintenant désabonné</div>
                        ";
                        break;
                        //succes 5 l'utilisateur a réussi à s'abonner
                    case '5':
                        $res.="
            <div class='succ'>vous etes maintenant abonné</div>
                        ";
                        break;
                }
                $res.="<br>";

            }
        $render = new ActionListTouite();
        $r = $render->execute();
        $res.=$r;
        return $res;
    }
}
