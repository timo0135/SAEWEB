<?php
namespace iutnc\touiter\dispatch;

use iutnc\touiter\action\ActionAfficherAbonnement;
use iutnc\touiter\action\ActionAfficherAbonnes;
use iutnc\touiter\action\ActionAfficherScoreMoyen;
use iutnc\touiter\action\ActionMenuTag;
use iutnc\touiter\action\ActionPageTag;
use iutnc\touiter\action\ActionProfilUser;
use iutnc\touiter\action\ActionPublishTouite;
use iutnc\touiter\action\ActionSubscribe;
use iutnc\touiter\action\ActionAfficherSettings;
use iutnc\touiter\action\ChoiceAction;
use iutnc\touiter\db\ConnectionFactory;
use iutnc\touiter\action\ActionRechercherTag;
use iutnc\touiter\manip\ManipPagination;
use iutnc\touiter\manip\ManipSubscribe;
use \iutnc\touiter\auth\Auth;
use iutnc\touiter\manip\ManipDislike;
use iutnc\touiter\manip\ManipLike;
use iutnc\touiter\action\ActionAfficherReponseTouite;
use iutnc\touiter\manip\ManipSupTouite;
use \iutnc\touiter\action\AddUserAction;
use \iutnc\touiter\action\SigninAction;


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
                    Auth::register($_POST['email'],$_POST['mot_de_passe'],$_POST['mot_de_passe_conf'],$_POST['nom'],$_POST['prenom']);
                }else{
                    $action = new AddUserAction();
                    $this->renderPage($action->execute());
                }
                break;
            case "connexion":
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    Auth::authenticate($_POST['email'],$_POST['mot_de_passe']);
                }else{
                    $action = new SigninAction();
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
            case "sup":
                $s=new ManipSupTouite();
                $s->execute();
                header("location:index.php");
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
                if($_SERVER['REQUEST_METHOD'] == 'GET'){         
                    $action= new ActionAfficherSettings();
                    $this->renderPage($action->execute());
                }elseif(isset($_POST['nemail'])){
                    Auth::change_email($_POST['nemail']);
                }elseif(isset($_POST['apassword']) && isset($_POST['npassword']) && isset($_POST['cpassword'])){
                    Auth::change_password($_POST['apassword'],$_POST['npassword'],$_POST['cpassword']);
                }
                break;
            case "afficherStatistique":
                $action=new ActionAfficherScoreMoyen();
                $this->renderPage($action->execute());
                break;
            case "afficherAbonnes":
                $action=new ActionAfficherAbonnes();
                $this->renderPage($action->execute());
                break;
            case "paginerTouite":
                if($_GET['augmenter']==="faux"){
                    ManipPagination::changerPagination(false);
                }else{
                    ManipPagination::changerPagination(true);
                }
                header("location:index.php");
            case 'back-office':{
                if($_SESSION['role'] == 100){
                    header("location:back-office.php");
                    exit();
                }else{
                    header("location:index.php?err=4");
                    exit();
                }
            }
        }

    }
    private function renderPage(string $html):void{
        // CONNEXION A LA BASE DE DONNEE //
        $bddPDO = ConnectionFactory::makeConnection();

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
    <a href='index.php?action=afficherAbonnement' style='width:100%'><button class='choice-button'>Abonnement&nbsp&nbsp<img src='icon/subscribed.png' style='width:30px;margin:0;'></button></a><br>
    <a href='index.php?action=afficherStatistique' style='width:100%'><button class='choice-button'>Statistique&nbsp&nbsp<img src='icon/stat.png' style='width:30px;margin:0;'></button></a><br>
    <a href='index.php?action=afficherAbonnes' style='width:100%'><button class='choice-button'>Abonnés&nbsp&nbsp<img src='icon/subscribe.png' style='width:30px;margin:0;'></button></a><br>";

    if($_SESSION['role'] == '100'){
        $res.="<a href='index.php?action=back-office' style='width:100%'><button class='choice-button'>back-office&nbsp&nbsp<img src='icon/back.png' style='width:30px;margin:0;'></button></a><br>";
    }

}
$res.="
</div>
<div class='tag-menu'>
";

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