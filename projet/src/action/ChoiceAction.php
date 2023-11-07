<?php
namespace iutnc\deefy\action;

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
            $res.= "<img src='oiseau.png' style='width:100px;position:absolute;top:15px;right:15px;'>";
            $res.= "
<div class='title'>TWEETER</div><br>
<br><br>
<a href='index.php?action=inscription'>s'inscrire</a>
<a href='index.php?action=connexion'>se connecter</a>
<a href='index.php?action=deconnexion'>deconnexion</a>
            ";
            return $res;
    }
}
