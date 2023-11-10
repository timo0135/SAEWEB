<?php
namespace iutnc\deefy\dispatch;
use iutnc\deefy\action\ActionAfficherAbonnement;
use iutnc\deefy\action\ActionMenuTag;
use iutnc\deefy\action\ActionPageTag;
use iutnc\deefy\action\ActionProfilUser;
use iutnc\deefy\action\ActionPublishTouite;
use iutnc\deefy\action\ActionSubscribe;
use iutnc\deefy\action\ActionAfficherSettings;
use iutnc\deefy\action\ChoiceAction;
use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\action\ActionRechercherTag;

use iutnc\deefy\manip\ManipSubscribe;



use iutnc\deefy\manip\ManipDislike;
use iutnc\deefy\manip\ManipLike;

use iutnc\deefy\action\ActionAfficherReponseTouite;


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
                $action = new ChoiceAction();
                $this->renderPage($action->execute());
            break;
            case "showPageTag":
                $action =new ActionMenuTag();
                $this->renderPage($action->execute());
                break;
            case "rechercherTag":
                $action=new ActionRechercherTag();
                $this->renderPage($action->execute());
                break;
            case "page-tag":
                if(isset($_GET['id_tag'])){
                    $action= new ActionPageTag();
                    $this->renderPage($action->execute());
                }else{
                    $action= new ChoiceAction();
                    $this->renderPage($action->execute());
                }
                break;
            case "voirPlus":
                $t=new ActionAfficherReponseTouite();
                $this->renderPage($t->execute());
                break;
            case "afficherAbonnement":
                $action=new ActionAfficherAbonnement();
                $this->renderPage($action->execute());
                break;
            case "publierTouite":
                $action=new ActionPublishTouite();
                $this->renderPage($action->execute());
                break;
            case "like":
                $l=new ManipLike();
                $l->execute();
                $t=new ActionAfficherReponseTouite();
                $this->renderPage($t->execute());
                break;
            case "dislike":
                $l=new ManipDislike();
                $l->execute();
                $t=new ActionAfficherReponseTouite();
                $this->renderPage($t->execute());
                break;
            case "page-user":
                $action= new ActionProfilUser();
                $this->renderPage($action->execute());
                break;
            case "subscribe":
                if(!isset($_SESSION['id'])){
                    header("location:index.php?err=2");
                    exit();
                }
                if(isset($_GET['iduser'])){
                    ManipSubscribe::subscribe($_GET['iduser'],$_SESSION['id']);
                }else{
                    header("location:index.php?err=3");
                    exit();
                }
                break;

            case "subscribeTag":
                if(!isset($_SESSION['id'])){
                    header("location:index.php?err=2");
                    exit();
                }
                if($_SERVER['REQUEST_METHOD']==='POST'&&isset($_GET['id_tag'])){
                    ManipSubscribe::subscribeTag($_GET['id_tag'],$_SESSION['id']);
                }else{
                    header("location:index.php?err=3");
                    exit();
                }
            case "settings":
                $action= new ActionAfficherSettings();
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
<body>
<img class='title' src='icon/touiter.png' alt='image du texte TOUITER'><br>
<br><br>
<div class='profil'>
<img src='icon/bird.png' style='width:100px;margin:5% auto;'><br>";
if(isset($_SESSION['id'])){
    $res.= "
    <div class='down'>
        <div class='sub-menu'>
        <a href='index.php' class='a-sub-menu'><img src='icon/home.png' class='img-sub-menu'></button></a>
        <a href='index.php?action=page-user' class='a-sub-menu'><img src='icon/profil.png' class='img-sub-menu'></button></a>
        <a href='index.php?action=settings' class='a-sub-menu'><img src='icon/settings.png' class='img-sub-menu'></button></a>
        <a href='index.php?action=deconnexion' class='a-sub-menu'><img src='icon/logout.png' class='img-sub-menu'></button></a>
        </div>
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
    $res.= "
    <a href='index.php?action=showPageTag' style='width:100%'><button class='choice-button'>Tag&nbsp&nbsp<img src='icon/hashtag.png' style='width:30px;margin:0;'></button></a><br>
    <a href='index.php?action=publierTouite' style='width:100%'><button class='choice-button'>Ajouter Touite&nbsp&nbsp<img src='icon/plus.png' style='width:30px;margin:0;'></button></a><br>
    <a href='index.php?action=afficherAbonnement' style='width:100%'><button class='choice-button'>Abonnement&nbsp&nbsp<img src='icon/subscribed.png' style='width:30px;margin:0;'></button></a><br>";
}
$res.="
</div>
<div class='tag-menu'>
";

// CONNEXION A LA BASE DE DONNEE //
$bddPDO = ConnectionFactory::makeConnection();

$commande="SELECT tag.id_tag AS id,tag.label AS lb,count(*) AS nb FROM tag JOIN touite2tag ON touite2tag.id_tag = tag.id_tag GROUP BY tag.id_tag ORDER BY count(*) DESC;";
$result=$bddPDO->query($commande);
$res.="<h2 class='titre-menu-tag'>Meilleurs Tags :</h2><br>";
$i=1;
while($row = $result->fetch()){
    $res.="<a class='lst-tag' href='index.php?action=page-tag&id_tag=".$row['id']."'>$i- ".$row['lb']."&nbsp(".$row['nb'].")</a><br>";
    $i++;
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