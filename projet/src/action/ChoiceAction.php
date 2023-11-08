<?php
namespace iutnc\deefy\action;

use iutnc\deefy\renderer\RendererListTouite;

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
                }
                $res.="<br>";

            }
        $render = new RendererListTouite();
        $r = $render->render();
        $res.=$r;
        return $res;
    }
}
