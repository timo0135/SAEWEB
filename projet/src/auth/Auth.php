<?php
namespace iutnc\touiter\auth;


use iutnc\touiter\db\ConnectionFactory;

class Auth{

    public static function authenticate(String $email,String $mdp){
        if(isset($_SESSION['id'])){
            // L'utilisateur est déjà connecté //
            // Erreur n°1 de choisir//
            header('location:index.php?err=1');
            exit();
        }
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            // Le formulaire n'a pas été remplit //
            // Erreur n°1 de connexion//
            header('location:index.php?action=connexion&err=1');
            exit();
        }
        if(empty($email) || empty($mdp)){
            // Des informations sont manquantes //
            // Erreur n°2 de connexion//
            header('location:index.php?action=connexion&err=2');
            exit();
        }
        
        // CONNEXION A LA BASE DE DONNEE //
        $bddPDO = ConnectionFactory::makeConnection();
        
        
        $commande = "
        SELECT id_user,password,role FROM USER WHERE 
        email = ?";

        $res = $bddPDO->prepare($commande);
        $email = filter_var ($email,FILTER_SANITIZE_EMAIL);
        $res->bindParam(1,$email);
        $res -> execute();
        
        if($row = $res -> fetch()){
            if(!password_verify(filter_var ($mdp,FILTER_SANITIZE_SPECIAL_CHARS),$row['password'])){
                // Des informations sont incorrectes //
                // Erreur n°3 de connexion//
                header('location:index.php?action=connexion&err=3');
                exit();
            }
            // Les informations sont bonnes //
            // Succes n°2 de choisir//
            $_SESSION['id'] = $row['id_user'];
            $_SESSION['role'] = $row['role'];
            header("location:index.php?succ=2");
            exit();
        }else{
            // Des informations sont incorrectes //
            // Erreur n°3 de connexion//
            header('location:index.php?action=connexion&err=3');
            exit();
        }
    }

    public static function register(String $email,String $mdp,String $mdpc,String $nom,String $prenom){
        if(isset($_SESSION['id'])){
            // L'utilisateur est déjà connecté //
            // Erreur n°1 de choisir//
            header('location:index.php?err=1');
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            // Le formulaire n'a pas été remplit //
            // Erreur n°1 de inscription//
            header('location:index.php?action=inscription&err=1');
            exit();
        }

        if(empty($email) || empty($mdp) || empty($mdpc) || empty($nom) || empty($prenom) ){
            // Des informations sont manquantes //
            // Erreur n°2 de inscription//
            header("location:index.php?action=inscription&err=2");
            exit();
        }

        if($mdp!=$mdpc){
            // Les mots de passes ne sont pas les mêmes //
            // Erreur n°3 de inscription//
            header("location:index.php?action=inscription&err=3");
            exit();
        }

        // CONNEXION A LA BASE DE DONNEE //
        $bddPDO = ConnectionFactory::makeConnection();

        $commande = "SELECT * FROM USER WHERE email = ?";
        $res = $bddPDO->prepare($commande);
        $email = filter_var ( $email,FILTER_SANITIZE_EMAIL);
        $res->bindParam(1,$email);
        $res -> execute();
        if($res -> fetch()){
            // L'email est déjà utilisé //
            // Erreur n°4 de inscription//
            header("location:index.php?action=inscription&err=4");
            exit();
        }


        // Insertion des données du nouvel utilisateur dans la base de donnée //
        $email = filter_var ( $email,FILTER_SANITIZE_EMAIL);
        $password = password_hash(filter_var ($mdp,FILTER_SANITIZE_SPECIAL_CHARS),PASSWORD_DEFAULT);
        $prenom = filter_var($prenom,FILTER_SANITIZE_SPECIAL_CHARS);
        $nom = filter_var($nom,FILTER_SANITIZE_SPECIAL_CHARS);
        $commande = "
        INSERT INTO USER(email,password,firstname,lastname,role) 
        VALUES (?,?,?,?,1)";
        $res = $bddPDO->prepare($commande);
        $res->bindParam(1,$email);
        $res->bindParam(2,$password);
        $res->bindParam(3,$prenom);
        $res->bindParam(4,$nom);

        try{
            $res -> execute($commande);
            // Succes de l'inscription //
            // Succes n°1 de choisir//
            header("location:index.php?succ=1");
            exit();
        }catch (\Exception $e)  {
            // Erreur d'insertion //
            // Erreur n°5 de inscription//
            header("location:index.php?action=inscription&err=5");
            exit();
        }
                
                
    }

    public static function change_email(String $email){
        if(!isset($_SESSION['id'])){
            // L'utilisateur n'est pas connecté //
            // Erreur n°2 de choisir//
            header('location:index.php?err=2');
            exit();
        }
        
        // CONNEXION A LA BASE DE DONNEE //
        $bddPDO = ConnectionFactory::makeConnection();

        $email = filter_var ( $email,FILTER_SANITIZE_EMAIL);
        $commande = "SELECT * FROM USER WHERE email = ?";
        $res = $bddPDO->prepare($commande);
        $res->bindParam(1,$email);
        $res->execute();
        if( $res->fetch()){
            // L'email est déjà utilisé //
            // Erreur n°1 de settings//
            header('location:index.php?action=settings&err=1');
            exit();
        }
        $commande = "UPDATE USER SET email = ? WHERE id_user = ?";
        $res = $bddPDO->prepare($commande);
        $res->bindParam(1,$email);
        $res->bindParam(2,$_SESSION['id']);
        $res->execute();
        // L'email à été modifié //
        header('location:index.php?action=settings&succ=1');
        exit();
    }

    public static function change_password(String $apassword,String $npassword,String $cpassword){
        if(!isset($_SESSION['id'])){
            // L'utilisateur n'est pas connecté //
            // Erreur n°2 de choisir//
            header('location:index.php?err=2');
            exit();
        }

        if($npassword!=$cpassword){
            // Les mots de passes ne sont pas les mêmes //
            // Erreur n°3 de inscription//
            header("location:index.php?action=inscription&err=3");
            exit();
        }

        // CONNEXION A LA BASE DE DONNEE //
        $bddPDO = ConnectionFactory::makeConnection();


        $commande = "
        SELECT password FROM USER WHERE 
        id_user = ?";

        $res = $bddPDO->prepare($commande);
        $res->bindParam(1,$_SESSION['id']);
        $res -> execute();
        
        if($row = $res -> fetch()){
            if(!password_verify(filter_var ($apassword,FILTER_SANITIZE_SPECIAL_CHARS),$row['password'])){
                // Des informations sont incorrectes //
                header('location:index.php?action=settings&err=2');
                exit();
            }
            // Les informations sont bonnes //

            // Modification du mot de passe //
                $password = password_hash(filter_var ($npassword,FILTER_SANITIZE_SPECIAL_CHARS),PASSWORD_DEFAULT);
                $commande = "
                UPDATE USER SET password = ? WHERE id_user=?";
                $res = $bddPDO->prepare($commande);
                $res->bindParam(1,$password);
                $res->bindParam(2,$_SESSION['id']);

                $res -> execute();
                
                header("location:index.php?action=settings&succ=2");
                exit();
        }else{
            // Des informations sont incorrectes //
            header('location:index.php?action=settings&err=3');
            exit();
        }




    }

}


?>