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
<div class='title'>TOUITER</div><br>
<br><br>
<div class='profil'>";
if(isset($_SESSION['id'])){
    $res.= "
    <div class='down'>
    <a href='index.php?action=deconnexion'><button>se deconnecter</button></a>
    </div>
    ";
}else{
    $res.="
    <a href='index.php?action=connexion'><button>se connecter</button></a>
    <a href='index.php?action=inscription'><button>s'inscrire</button></a>";
}
$res.= "
</div>
";
            return $res;
    }
}
