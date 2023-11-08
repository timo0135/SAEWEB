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
            $res.= "
<div class='title'>TOUITER</div><br>
<br><br>
<div class='profil'>";
if(isset($_SESSION['id'])){
    $res.= "
    <img src='bird.svg' style='width:100px;margin:5% auto;'>
    <div class='down'>
    <a href='index.php?action=deconnexion' class='deconnexion'>Deconnexion</a>
    </div>
    ";
}else{
    $res.="
    <img src='bird.svg' style='width:100px;margin:5% auto;'><br>
    <a href='index.php?action=connexion' style='width:100%'><button class='user-button'>Connexion</button></a><br>
    <a href='index.php?action=inscription' style='width:100%'><button class='user-button'>Inscription</button></a><br>";
}
    $res.= "
    <a href='index.php' style='width:100%'><button class='choice-button'>Home</button></a><br>
    <a href='index.php' style='width:100%'><button class='choice-button'>Abonnement</button></a>
</div>
";
    return $res;
    }
}
