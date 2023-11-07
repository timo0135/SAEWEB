<?php

namespace iutnc\deefy\action;

use iutnc\deefy\db\ConnectionFactory;

class ActionDeleteTouitte extends Action
{

    public function execute(): string
    {
        $sql="DELETE FROM TOUITE where id_touite=?";
        $bdo=ConnectionFactory::makeConnection();
        $resultSet=$bdo->prepare($sql);
        $resultSet->bindParam(1,$_GET['id']);
        $resultSet->execute();
        return "<p>Votre twouitte a était supprimé</p>";
    }
}