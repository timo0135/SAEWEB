<?php
namespace iutnc\deefy\auth;


use iutnc\deefy\db\ConnectionFactory;

class Auth{

    public static function authenticate(String $email,String $mdp){
        session_start();
        if(isset($_SESSION['id'])){
            // L'utilisateur est déjà connecté //
            // Erreur n°1 de index.php//
            header('location:index.php?err=1');
            exit();
        }
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            // Le formulaire n'a pas été remplit //
            // Erreur n°1 de index.php?action=connexion//
            header('location:index.php?action=connexion&err=1');
            exit();
        }
        if(empty($email) || empty($mdp)){
            // Des informations sont manquantes //
            // Erreur n°2 de index.php?action=connexion//
            header('location:index.php?action=connexion&err=2');
            exit();
        }
        
        // CONNEXION A LA BASE DE DONNEE //
        $bddPDO = ConnectionFactory::makeConnection();
        
        
        $commande = "
        SELECT id_user,password FROM User WHERE 
        email = '".filter_var ($email,FILTER_SANITIZE_EMAIL)."'";
        
        $res = $bddPDO -> query($commande);
        
        if($row = $res -> fetch()){
            if(!password_verify(filter_var ($mdp,FILTER_SANITIZE_SPECIAL_CHARS),$row['password'])){
                // Des informations sont incorrectes //
                // Erreur n°3 de index.php?action=connexion//
                header('location:index.php?action=connexion&err=3');
                exit();
            }
            // Les informations sont bonnes //
            // Succes n°2 de index.php//
            $_SESSION['id'] = $row['id_user'];
            header("location:index.php?succ=2");
            exit();
        }else{
            // Des informations sont incorrectes //
            // Erreur n°3 de index.php?action=connexion//
            header('location:index.php?action=connexion&err=3');
            exit();
        }
    }

    public static function register(String $email,String $mdp,String $mdpc,String $nom,String $prenom){
        session_start();
        if(isset($_SESSION['id'])){
            // L'utilisateur est déjà connecté //
            // Erreur n°1 de index.php//
            header('location:index.php?err=1');
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            // Le formulaire n'a pas été remplit //
            // Erreur n°1 de inscription-form.php//
            header('location:index.php?action=inscription&err=1');
            exit();
        }

        if(empty($email) || empty($mdp) || empty($mdpc) || empty($nom) || empty($prenom) ){
            // Des informations sont manquantes //
            // Erreur n°2 de inscription-form.php//
            header("location:index.php?action=inscription&err=2");
            exit();
        }

        if($mdp!=$mdpc){
            // Les mots de passes ne sont pas les mêmes //
            // Erreur n°3 de inscription-form.php//
            header("location:index.php?action=inscription&err=3");
            exit();
        }

        // CONNEXION A LA BASE DE DONNEE //
        $bddPDO = ConnectionFactory::makeConnection();

        $commande = "SELECT * FROM user WHERE email = '".filter_var ( $email,FILTER_SANITIZE_EMAIL)."'";
        $res = $bddPDO -> query($commande);
        if($res -> fetch()){
            // L'email est déjà utilisé //
            // Erreur n°4 de inscription-form.php//
            header("location:index.php?action=inscription&err=4");
            exit();
        }


        // Insertion des données du nouvel utilisateur dans la base de donnée //
        $commande = "
        INSERT INTO User(email,password,firstname,lastname,role) 
        VALUES ('".filter_var ( $email,FILTER_SANITIZE_EMAIL)."','"
        .password_hash(filter_var ($mdp,FILTER_SANITIZE_SPECIAL_CHARS),PASSWORD_DEFAULT)."','".
        filter_var($nom,FILTER_SANITIZE_SPECIAL_CHARS)."','".
        filter_var($prenom,FILTER_SANITIZE_SPECIAL_CHARS)."',1)";

        try{
            $bddPDO -> query($commande);
            // Succes de l'inscription //
            // Succes n°1 de index.php//
            header("location:index.php?succ=1");
            exit();
        }catch (\Exception $e)  {
            // Erreur d'insertion //
            // Erreur n°5 de inscription-form.php//
            header("location:index.php?action=inscription&err=5");
            exit();
        }
                
                
    }

}


?>