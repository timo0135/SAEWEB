<?php

namespace iutnc\deefy\action;

use iutnc\deefy\db\ConnectionFactory;

class ActionAfficherScoreMoyen extends Action
{

    public function execute(): string
    {
        $res="";
        $bdd=ConnectionFactory::makeConnection();
        $sql="Select id_touite from touite where id_user=?";
        $nbLike="Select count(*) as count from `like` where id_touite=? and `like`.like=1";
        $nbDislike="Select count(*) as count from `like` where id_touite=? and `like`.like=0";
        $resultSet=$bdd->prepare($sql);
        $resultSet->bindParam(1,$_SESSION['id']);
        $resultSet->execute();
        $longeur=0;
        $like=0;
        $dislike=0;
        while ($row=$resultSet->fetch()){
            $longeur++;
            $resultLike=$bdd->prepare($nbLike);
            $resultDislike=$bdd->prepare($nbDislike);
            $resultLike->bindParam(1,$row['id_touite']);
            $resultDislike->bindParam(1,$row['id_touite']);
            $resultLike->execute();
            $resultDislike->execute();
            $rowLike=$resultLike->fetch();
            $rowDislike=$resultDislike->fetch();
            $like+=$rowLike['count'];
            $dislike+=$rowDislike['count'];
        }
        $res.="
        <fieldset class='touite-box'><legend><h1>Statistiques</h1></legend>
            ";
        if($longeur===0){
            $res.= "<p>Vous n'avez toujours pas posté de touite cliquer ici pour poster un touite</p>";
        }else{
            $moyenneLike=$like/$longeur;
            $moyenneDislike=$dislike/$longeur;
            $moyenneLike=round($moyenneLike,2);
            $moyenneDisike=round($moyenneDislike,2);
            $res.= "<p>Vous avez posté $longeur touite.<br><br>Vous avez en moyenne obtenue ".$moyenneLike." like et ".$moyenneDisike." dislike</p><br>";
        }
        $res.= "
        </fieldset>";
        return $res;
     }
}