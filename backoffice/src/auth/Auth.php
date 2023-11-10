<?php
namespace iutnc\backoffice\auth;


use iutnc\backoffice\db\ConnectionFactory;

class Auth{

    public static function authenticate(String $email,String $mdp){
        
        // CONNEXION A LA BASE DE DONNEE //
        ConnectionFactory::setConfig("db.config.ini");
        $bddPDO = ConnectionFactory::makeConnection();
        
        
        $commande = "
        SELECT id_user,password,role FROM User WHERE 
        email = ?";

        $res = $bddPDO->prepare($commande);
        $email = filter_var ($email,FILTER_SANITIZE_EMAIL);
        $res->bindParam(1,$email);
        $res -> execute();
        
        if($row = $res -> fetch()){
            if(!password_verify(filter_var ($mdp,FILTER_SANITIZE_SPECIAL_CHARS),$row['password'])){
                // Des informations sont incorrectes //
                header('location:connexion.php?err=1');
                exit();
            }
            if($row['role'] != '100'){
                // Pas les droits pour se connecter //
                header('location:connexion.php?err=2');
                exit();

            }
            // Les informations sont bonnes //
            $_SESSION['ida'] = $row['id_user'];
            header("location:index.php");
            exit();
        }else{
            // Des informations sont incorrectes //
            header('location:connexion.php?err=3');
            exit();
        }
    }
    
}


?>