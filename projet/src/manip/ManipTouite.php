<?php

namespace iutnc\touiter\manip;

use iutnc\touiter\db\ConnectionFactory;

class ManipTouite
{
    // Méthode pour ajouter un touite
    public static function add_touite(): void
    {
        $answer = null;

        // Vérifie s'il s'agit d'une réponse à un touite
        if (isset($_GET['id'])) {
            $answer = $_GET['id'];
        }

        // Récupère le message du touite
        $message = $_POST['message'];

        // Établie une connexion à la base de données
        $bdd = ConnectionFactory::makeConnection();

        // Obtient la date actuelle
        $date = date('Y-m-d H:i:s');

        // Prépare la requête d'insertion du touite dans la base de données
        $sql = "INSERT INTO TOUITE (message, date, id_user, answer, path, description) VALUES (?, ?, ?, ?, ?, ?)";
        $resultset = $bdd->prepare($sql);
        $resultset->bindParam(1, $message);
        $resultset->bindParam(2, $date);
        $idUser = $_SESSION['id'];
        $resultset->bindParam(3, $idUser);
        $resultset->bindParam(4, $answer);

        // Vérifie si une image a été fourni dans le formulaire
        if (!isset($_FILES['image'])) {
            // Si ce n'est pas le cas lors on mets à null
            $dest = null;
            $description = null;
        } else {
            // C'est le répertoire de destination pour les fichiers image
            $upload_dir = "./image/";

            // Vérifie si le champ de fichier image est vide
            if (empty($_FILES['image']['name'])) {
                // Si c'est vide alors on mets à null
                $dest = null;
                $description = null;
            } else {
                // On donne un nom unique
                $filename = uniqid();

                // Récupère le fichier temporaire de l'image
                $tmp = $_FILES['image']['tmp_name'];

                // Extrait l'extension du fichier image
                $tabExtension = explode('.', $_FILES['image']['name']);
                $extension = strtolower(end($tabExtension));

                // Vérifie si le fichier image est valide (type et extension)
                if (
                    ($_FILES['image']['error'] === UPLOAD_ERR_OK) &&
                    (
                        ($_FILES['image']['type'] === 'image/png') ||
                        ($_FILES['image']['type'] === 'image/jpg') ||
                        ($_FILES['image']['type'] === 'image/jpeg')
                    )
                ) {
                    // Construit le chemin complet de destination pour le fichier image
                    $dest = $upload_dir . $filename . "." . $extension;

                    // Déplace le fichier vers le répertoire des images
                    if (!move_uploaded_file($tmp, $dest)) {
                        // Si le déplacement échoue, on lance une exception
                        throw new \Exception("Échec du téléchargement de l'image.");
                    } else {
                        // Si le déplacement réussit, on récupère la description de l'image
                        $description = $_POST['description'];
                    }
                } else {
                    // Si l'extension n'est pas correcte alors on lance une exception
                    throw new \Exception("Extension de fichier invalide.");
                }
            }
        }


        $resultset->bindParam(5, $dest);
        $resultset->bindParam(6, $description);
        $resultset->execute();

        // Récupère l'id du touite ajouté
        $sql = "SELECT id_touite FROM TOUITE WHERE id_user=? AND date=?";
        $resultset = $bdd->prepare($sql);
        $resultset->bindParam(1, $_SESSION['id']);
        $resultset->bindParam(2, $date);
        $resultset->execute();
        $row = $resultset->fetch();
        $id_touite = $row['id_touite'];

        // Sépare le message du touite en mots pour vérifier s'il y a des tags
        $tabPartieTouitte = explode(" ", $_POST['message']);

        // Parcoure les mots et vérifie s'ils sont des tags (commencent par #)
        foreach ($tabPartieTouitte as $t) {
            if (substr($t, 0, 1) === '#') {
                // Vérifie si le tag existe déjà dans la base de données
                $bdo = ConnectionFactory::makeConnection();
                $sql = "SELECT label FROM TAG WHERE label=?";
                $resultSet = $bdo->prepare($sql);
                $resultSet->bindParam(1, $t);
                $resultSet->execute();

                // Si le tag n'existe pas, on l'ajoute à la base de données
                if (!$resultSet->fetch()) {
                    $sql = "INSERT INTO TAG (label, description) VALUES (?, ?)";
                    $description = null;
                    $resultSet = $bdo->prepare($sql);
                    $resultSet->bindParam(1, $t);
                    $resultSet->bindParam(2, $description);
                    $resultSet->execute();
                }

                // Récupère l'id du tag
                $sql = "SELECT id_tag FROM TAG WHERE label=?";
                $resultSet = $bdo->prepare($sql);
                $resultSet->bindParam(1, $t);
                $resultSet->execute();
                $row = $resultSet->fetch();
                $id_tag = $row['id_tag'];

                // Vérifie si l'association entre le tag et le touite existe déjà
                $sql = "SELECT * FROM TOUITE2TAG WHERE id_tag=? AND id_touite=?";
                $resultSet = $bdo->prepare($sql);
                $resultSet->bindParam(1, $id_tag);
                $resultSet->bindParam(2, $id_touite);
                $resultSet->execute();

                // Si l'association n'existe pas, on l'ajoute à la base de données
                if (!$resultSet->fetch()) {
                    $sql = "INSERT INTO TOUITE2TAG VALUES (?, ?)";
                    $resultSet2 = $bdo->prepare($sql);
                    $resultSet2->bindParam(1, $id_tag);
                    $resultSet2->bindParam(2, $id_touite);
                    $resultSet2->execute();
                }
            }
        }
    }
}
