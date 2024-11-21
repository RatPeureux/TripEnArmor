<?php
// Connexion avec la bdd
include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// // Connexion à la base de données
// try {
//     $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
//     $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     echo json_encode(['success' => false, 'error' => 'Erreur de connexion à la base de données : ' . $e->getMessage()]);
//     exit;
// }

// Partie pour traiter la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Fonction pour calculer le prix minimum à partir des prix envoyés dans le formulaire
    function calculerPrixMin($prices)
    {
        $minPrice = null;

        foreach ($prices as $price) {
            if (isset($price['value']) && (is_null($minPrice) || $price['value'] < $minPrice)) {
                $minPrice = $price['value'];
            }
        }

        return $minPrice;
    }

    // Fonction pour extraire des informations depuis une adresse complète
    function extraireInfoAdresse($adresse)
    {
        $numero = substr($adresse, 0, 1);  // À adapter selon le format de l'adresse
        $odonyme = substr($adresse, 2);

        return [
            'numero' => $numero,
            'odonyme' => $odonyme,
        ];
    }

    // Récupération des données du formulaire
    $adresse = $_POST['user_input_autocomplete_address'];
    $code = $_POST['postal_code'];
    $ville = $_POST['locality'];
    $age = $_POST['age'];
    $duree = !empty($_POST['duree']) ? $_POST['duree'] : '00:00:00';

    // Vérification de la durée
    if (is_numeric($duree)) {
        $hours = floor($duree / 60);
        $minutes = $duree % 60;
        $dureeFormatted = sprintf('%02d:%02d:00', $hours, $minutes); // Format HH:MM:SS
    } else {
        // Si $duree n'est pas valide, définir une valeur par défaut ou lever une erreur
        $dureeFormatted = '00:00:00'; // Valeur par défaut
    }

    // Récupérer d'autres valeurs
    $capacite = $_POST['place'] ?? '';
    $nb_attractions = isset($_POST['parc-numb']) && is_numeric($_POST['parc-numb']) ? (int) $_POST['parc-numb'] : 0;
    $gamme_prix = $_POST['gamme_prix'] ?? '';
    $description = $_POST['description'] ?? '';
    $resume = $_POST['resume'] ?? '';
    $prestations = $_POST['newPrestationName'] ?? '';
    $prices = $_POST['prices'] ?? []; // Récupérer les prix
    $titre = $_POST['titre'] ?? null;

    $tag = $_POST['tag-input'];

    var_dump($prices);

    if ($titre === null) {
        echo "Le titre est null.";
        exit;
    } else {
        echo "Le titre est : " . htmlspecialchars($titre);
    }

    // Calculer le prix minimum parmi les tarifs
    $prixMin = calculerPrixMin($prices);

    // Insérer l'adresse dans la base de données
    $realAdresse = extraireInfoAdresse($adresse);

    $stmtAdresseOffre = $dbh->prepare("INSERT INTO sae_db._adresse (code_postal, ville, numero, odonyme, complement) VALUES (:postal_code, :locality, :numero, :odonyme, null)");
    $stmtAdresseOffre->bindParam(':postal_code', $code);
    $stmtAdresseOffre->bindParam(':locality', $ville);
    $stmtAdresseOffre->bindParam(':numero', $realAdresse['numero']);
    $stmtAdresseOffre->bindParam(':odonyme', $realAdresse['odonyme']);

    if ($stmtAdresseOffre->execute()) {
        $id_offre = $dbh->lastInsertId();  // Récupérer l'ID de l'offre insérée
        // // Redirigez vers l'accueil
        // header('location: ../../pages/accueil-pro.php);

        // Insérer les tarifs publics associés
        foreach ($prices as $price) {
            if (!isset($price['name']) || !isset($price['value'])) {
                echo "Erreur : données de prix invalides.";
                continue;
            }

            $age_min = (int) $age;  // Âge minimum par exemple
            $prix_min = is_numeric($price['value']) ? floatval($price['value']) : null;

            var_dump($age_min, $prix_min);  // Afficher les valeurs avant insertion

            $stmtInsertPrice = $dbh->prepare("INSERT INTO sae_db._tarif_public (titre, prix, id_offre) VALUES (:titre, :prix, :id_offre)");
            $stmtInsertPrice->bindParam(':titre', $price['name']);
            $stmtInsertPrice->bindParam(':prix', $price['value']);
            $stmtInsertPrice->bindParam(':id_offre', $id_offre);

            if (!$stmtInsertPrice->execute()) {
                echo "Erreur lors de l'insertion du prix : " . implode(", ", $stmtInsertPrice->errorInfo());
            }
            echo json_encode(['success' => true]);
            $dateCreation = date('Y-m-d H:i:s');
            $id_adresse = $dbh->lastInsertId();
            // Gérer les différentes catégories d'offres
            $activity = $_POST['activityType'];
            switch ($activity) {
                case 'activite':
                    // Insertion spécifique à l'activité
                    $stmtActivite = $dbh->prepare("INSERT INTO sae_db._activite (id_offre, est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, id_pro, id_adresse, duree, age_requis, prestations) VALUES (:id_offre, true, :description, :resume, :prix, :titre, :date_creation, null, null, null, :id_adresse, :duree, :age, :prestations)");
                    $stmtActivite->bindParam(':id_offre', $id_offre);
                    $stmtActivite->bindParam(':description', $description);
                    $stmtActivite->bindParam(':resume', $resume);
                    $stmtActivite->bindParam(':prix', $prixMin);
                    $stmtActivite->bindParam(':date_creation', $dateCreation);
                    $stmtActivite->bindParam(':id_adresse', $id_adresse);
                    $stmtActivite->bindParam(':duree', $dureeFormatted);
                    $stmtActivite->bindParam(':age', $age);
                    $stmtActivite->bindParam(':prestations', $prestations);
                    $stmtActivite->bindParam(':titre', $titre);

                    if ($stmtActivite->execute()) {
                        echo "Activité insérée avec succès.";
                        $stmtTags = $dbh->prepare("INSERT INTO sae_db._tag (nom) VALUES (:tag)");
                        $stmtTags->bindParam(':tag', $tag);
                        if ($stmtTags->execute()) {
                            $stmtActiviteTag = $dbh->prepare("INSERT INTO sae_db._tag_activite () VALUES ()");
                            if ($stmtActiviteTag->execute()) {
                                echo "Activité insérée avec succès.";
                                header('location: ../../pages/accueil-pro.php');
                            } else {
                                echo "Erreur lors de l insertion : " . implode(", ", $stmtActiviteTag->errorInfo());
                            }
                        } else {
                            echo "Erreur lors de l insertion : " . implode(", ", $stmtTags->errorInfo());
                        }


                    } else {
                        echo "Erreur lors de l insertion : " . implode(", ", $stmtActivite->errorInfo());
                    }

                    break;

                case 'visite':

                    $stmtVisite = $dbh->prepare("INSERT INTO sae_db._visite(id_offre, est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, id_pro, id_adresse, duree, avec_guide) VALUES (:id_offre, true, :description, :resume, :prix, :titre, :date_creation, null, null, null, :id_adresse, :duree, false)");
                    $stmtVisite->bindParam(':id_offre', $id_offre);
                    $stmtVisite->bindParam(':description', $description);
                    $stmtVisite->bindParam(':resume', $resume);
                    $stmtVisite->bindParam(':prix', $prix);
                    $stmtVisite->bindParam(':date_creation', $dateCreation);
                    $stmtVisite->bindParam(':id_adresse', $id_adresse);
                    $stmtVisite->bindParam(':duree', $duree);
                    $stmtVisite->bindParam(':titre', $titre);

                    if ($stmtVisite->execute()) {
                        echo "Visite insérée avec succès.";
                        header('location: ../../pages/accueil-pro.php');
                    } else {
                        echo "Erreur lors de l'insertion : " . implode(", ", $stmtVisite->errorInfo());
                    }

                    break;

                case 'spectacle':

                    var_dump($capacite);

                    $stmtSpectacle = $dbh->prepare("INSERT INTO sae_db._spectacle(id_offre, est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, id_pro, id_adresse, capacite, duree) VALUES (:id_offre, true, :description, :resume, :prix, :titre, :date_creation, null, null, null, :id_adresse, :capacite, :duree)");
                    $stmtSpectacle->bindParam(':id_offre', $id_offre);
                    $stmtSpectacle->bindParam(':description', $description);
                    $stmtSpectacle->bindParam(':resume', $resume);
                    $stmtSpectacle->bindParam(':prix', $prixMin);
                    $stmtSpectacle->bindParam(':date_creation', $dateCreation);
                    $stmtSpectacle->bindParam(':id_adresse', $id_adresse);
                    $stmtSpectacle->bindParam(':capacite', $capacite);
                    $stmtSpectacle->bindParam(':duree', $duree);
                    $stmtSpectacle->bindParam(':titre', $titre);

                    if ($stmtSpectacle->execute()) {
                        echo "Spectacle insérée avec succès.";
                        header('location: ../../pages/accueil-pro.php');
                    } else {
                        echo "Erreur lors de l insertion : " . implode(", ", $stmtSpectacle->errorInfo());
                    }

                    break;

                case 'parc_attraction':

                    $stmtAttraction = $dbh->prepare("INSERT INTO sae_db._parc_attraction(id_offre, est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, id_pro, id_adresse, nb_attractions, age_requis) VALUES (:id_offre, true, :description, :resume, :prix, :titre, :date_creation, null, null, null, :id_adresse, :nb_attraction, :age)");
                    $stmtAttraction->bindParam(':id_offre', $id_offre);
                    $stmtAttraction->bindParam(':description', $description);
                    $stmtAttraction->bindParam(':resume', $resume);
                    $stmtAttraction->bindParam(':prix', $prixMin);
                    $stmtAttraction->bindParam(':date_creation', $dateCreation);
                    $stmtAttraction->bindParam(':id_adresse', $id_adresse);
                    $stmtAttraction->bindParam(':nb_attraction', $nb_attractions);
                    $stmtAttraction->bindParam(':age', $age);
                    $stmtAttraction->bindParam(':titre', $titre);

                    if ($stmtAttraction->execute()) {
                        echo "Parc d'attraction insérée avec succès.";
                        header('location: ../../pages/accueil-pro.php');
                    } else {
                        echo "Erreur lors de l'insertion : " . implode(", ", $stmtAttraction->errorInfo());
                    }

                    break;

                case 'restauration':

                    $stmtRestauration = $dbh->prepare("INSERT INTO sae_db._restauration(id_offre, est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, id_pro, id_adresse, gamme_prix) VALUES (:id_offre, true, :description, :resume, :prix, :titre, :date_creation, null, null, null, :id_adresse, :gamme_prix)");
                    $stmtRestauration->bindParam(':id_offre', $id_offre);
                    $stmtRestauration->bindParam(':description', $description);
                    $stmtRestauration->bindParam(':resume', $resume);
                    $stmtRestauration->bindParam(':prix', $prixMin);
                    $stmtRestauration->bindParam(':date_creation', $dateCreation);
                    $stmtRestauration->bindParam(':id_adresse', $id_adresse);
                    $stmtRestauration->bindParam(':gamme_prix', $gamme_prix);
                    $stmtRestauration->bindParam(':titre', $titre);

                    if ($stmtRestauration->execute()) {
                        echo "Restauration insérée avec succès.";
                        header('location: ../../pages/accueil-pro.php');
                    } else {
                        echo "Erreur lors de l'insertion : " . implode(", ", $stmtRestauration->errorInfo());
                    }

                    break;

                default:
                    echo "Veuillez sélectionner une activité.";
                    exit;
            }
        }
    } else {
        echo "Erreur lors de l'insertion dans la table Adresse : " . implode(", ", $stmtAdresseOffre->errorInfo());
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Aucune soumission de formulaire détectée.']);
}
?>