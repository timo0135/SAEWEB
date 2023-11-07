<?php
namespace iutnc\deefy\dispatch;
session_start();

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
        }

    }
    private function renderPage(string $html):void{
        echo "<!DOCTYPE html>
<html lang=en>
<head>
    <meta charset=UTF-8>
    <title>index</title>
    <link href='style.css' rel='stylesheet'>
</head>
<body>
    $html
</body>
</html>";
    }
}