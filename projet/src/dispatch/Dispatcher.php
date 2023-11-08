<?php
namespace iutnc\deefy\dispatch;
use iutnc\deefy\action\ActionPageTag;
use iutnc\deefy\action\ActionRechercherTag;


class Dispatcher
{
    private string $action;

    public function __construct(string $s){
        $this->action=$s;
    }
    public function run():void{

        switch ($this->action){
            case "inscription":
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    \iutnc\deefy\auth\Auth::register($_POST['email'],$_POST['mot_de_passe'],$_POST['mot_de_passe_conf'],$_POST['nom'],$_POST['prenom']);
                }else{
                    $action = new \iutnc\deefy\action\AddUserAction();
                    $this->renderPage($action->execute());
                }
                break;
            case "connexion":
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    \iutnc\deefy\auth\Auth::authenticate($_POST['email'],$_POST['mot_de_passe']);
                }else{
                    $action = new \iutnc\deefy\action\SigninAction();
                    $this->renderPage($action->execute());
                }
                break;
            case "deconnexion":
                unset($_SESSION['id']);
                header("location:index.php?succ=3");
                exit();
            case "choisir":
                $action = new \iutnc\deefy\action\ChoiceAction();
                $this->renderPage($action->execute());
            break;
            case "showPageTag":
                $action =new ActionPageTag();
                $this->renderPage($action->execute());
                break;
            case "rechercherTag":
                $action=new ActionRechercherTag();
                $this->renderPage($action->execute());
                break;
        }

    }
    private function renderPage(string $html):void{
        $res="";
        $res.= "<!DOCTYPE html>
<html lang=en>
<head>
    <meta charset=UTF-8>
    <title>index</title>
    <link href='style.css' rel='stylesheet'>
</head>
<body><div class='title'>TOUITER</div><br>
<br><br>
<div class='profil'>
<img src='icon/bird.png' style='width:100px;margin:5% auto;'><br>";
if(isset($_SESSION['id'])){
    $res.= "
    <div class='down'>
    <a href='index.php?action=deconnexion' class='deconnexion'>Deconnexion</a>
    </div>
    ";
}else{
    $res.="
    <a href='index.php?action=connexion' style='width:100%'><button class='user-button'>Connexion</button></a><br>
    <a href='index.php?action=inscription' style='width:100%'><button class='user-button'>Inscription</button></a><br><br>";
}
$res.= "
<form method='post' action='index.php?action=rechercherTag'>
<input type='text' name='recherche' class='recherche' placeholder='Rechercher..'/><br><br>
</form>
<a href='index.php' style='width:100%'><button class='choice-button'>Home&nbsp&nbsp<img src='icon/home.png' style='width:30px;margin:0;'></button></a><br>";
if(isset($_SESSION['id'])){
    $res.= "<a href='index.php?action=showPageTag' style='width:100%'><button class='choice-button'>Tag&nbsp&nbsp<img src='icon/hashtag.png' style='width:30px;margin:0;'></button></a><br>
    <a href='index.php' style='width:100%'><button class='choice-button'>Abonnement&nbsp&nbsp<img src='icon/subscribers.png' style='width:30px;margin:0;'></button></a><br>";
}
$res.="
</div>
";
$res.=$html;
$res.="</body>
</html>";
echo $res;
    }
}