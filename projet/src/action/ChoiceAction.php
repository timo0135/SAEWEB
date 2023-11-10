<?php
namespace iutnc\deefy\action;

use iutnc\deefy\action\ActionListTouite;

class ChoiceAction extends Action{
    public function execute():string{
            $res="";
            if(isset($_GET['err'])){
                switch($_GET['err']){
                    case '1':
                        $res.="
            <div class='err'>Vous etes déjà connecté</div>
                        ";
                        break;
                    case '2':
                        $res.="
            <div class='err'>Vous devez etre connecté</div>
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
            <div class='succ'>Vous etes maintenant inscrit</div>
                        ";
                        break;
                    case '2':
                        $res.="
            <div class='succ'>Vous etes maintenant connecté</div>
                        ";
                        break;
                    case '3':
                        $res.="
            <div class='succ'>vous etes maintenant déconnecté</div>
                        ";
                        break;
                    case '4':
                        $res.="
            <div class='succ'>vous etes maintenant désabonné</div>
                        ";
                        break;
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
