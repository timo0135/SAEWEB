<?php

namespace iutnc\deefy\action;

use iutnc\deefy\db\ConnectionFactory;

class ActionAfficherScoreMoyen extends Action
{

    public function execute(): string
    {
        $bdd=ConnectionFactory::makeConnection();
        $sql="Select id_touite from touite where id_user=?";
        $nbLike="Select count(*) as count from like where id_touite=? and like.like=1";
        $nbDislike="Select count(*) as count from like where id_touite=? and like.like=0";
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
        if($longeur===0){
            return "<p>Vous n'avez toujours pas posté de touite cliquer ici pour poster un touite</p>";
        }
        return "<p>Vous avez posté $longeur touite.</p><br>Vous avez en moyenne obtenue ".$like/$longeur." like et ".$dislike/$longeur." dislike<br>";
    }
}