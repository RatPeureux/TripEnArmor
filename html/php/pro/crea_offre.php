<?php
include('../../../php-files/connect_params.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Partie pour traiter la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Fonction pour calculer le prix minimum à partir des prix envoyés dans le formulaire
    function calculerPrixMin($prices) {
        $minPrice = null;

        foreach ($prices as $price) {
            if (isset($price['value']) && (is_null($minPrice) || $price['value'] < $minPrice)) {
                $minPrice = $price['value'];
            }
        }

        return $minPrice;
    }

    // Récupérer les données soumises via POST
    $adresse = $_POST['user_input_autocomplete_address'];
    $code = $_POST['postal_code'];
    $ville = $_POST['locality'];
    $age = $_POST['age'];
    $duree = !empty($_POST['duree']) ? $_POST['duree'] : '00:00:00';
    if (is_numeric($duree)) {
        $hours = floor($duree / 60);
        $minutes = $duree % 60;
        $dureeFormatted = sprintf('%02d:%02d:00', $hours, $minutes); // Format HH:MM:SS
    } else {
        // Si $duree n'est pas valide, définir une valeur par défaut ou lever une erreur
        $dureeFormatted = '00:00:00';  // Valeur par défaut
    }
    $capacite = $_POST['place'] ?? '';
    $nb_attractions = isset($_POST['parc-numb']) && is_numeric($_POST['parc-numb']) ? (int)$_POST['parc-numb'] : 0;
    $gamme_prix = $_POST['gamme_prix'] ?? '';
    $description = $_POST['description'];
    var_dump($description);
    $resume = $_POST['resume'] ?? '';
    $prestations = $_POST['newPrestationName'] ?? '';
    $prices = $_POST['prices'] ?? [];  // Récupérer les prix
    $titre = $_POST['titre'] ?? null;
    

    var_dump($prices);  // Pour le débogage des prix reçus

    if ($titre === null) {
        echo "Le titre est null.";
        exit;
    } else {
        echo "Le titre est : " . htmlspecialchars($titre);
    }

    // Calculer le prix minimum parmi les tarifs
    $prixMin = calculerPrixMin($prices);

    // Fonction pour extraire des informations depuis une adresse complète
    function extraireInfoAdressse($adresse) {
        $numero = substr($adresse, 0, 1);  // À adapter selon le format de l'adresse
        $odonyme = substr($adresse, 2);

        return [
            'numero' => $numero,
            'odonyme' => $odonyme,
        ];
    }

    // Insérer l'adresse dans la base de données
    $realAdresse = extraireInfoAdressse($adresse);
    $stmtAdresseOffre = $dbh->prepare("INSERT INTO sae_db._adresse (code_postal, ville, numero, odonyme, complement_adresse) VALUES (:postal_code, :locality, :numero, :odonyme, null)");
    $stmtAdresseOffre->bindParam(':postal_code', $code);
    $stmtAdresseOffre->bindParam(':locality', $ville);
    $stmtAdresseOffre->bindParam(':numero', $realAdresse['numero']);
    $stmtAdresseOffre->bindParam(':odonyme', $realAdresse['odonyme']);

    if ($stmtAdresseOffre->execute()) {
        $adresseId = $dbh->lastInsertId();
        $dateCreation = date('Y-m-d H:i:s');

        // Insérer l'offre dans la table `Offre`
        $stmtOffre = $dbh->prepare("INSERT INTO sae_db._offre (est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, adresse_id) VALUES (true, :description, :resume, :prix, :titre, :date_creation, null, null, :adresse_id)");
        $stmtOffre->bindParam(':description', $description);
        $stmtOffre->bindParam(':resume', $resume);
        $stmtOffre->bindParam(':adresse_id', $adresseId);
        $stmtOffre->bindParam(':date_creation', $dateCreation);
        $stmtOffre->bindParam(':prix', $prixMin);
        $stmtOffre->bindParam(':titre', $titre);

        if ($stmtOffre->execute()) {
            $offreId = $dbh->lastInsertId();  // Récupérer l'ID de l'offre insérée
            // Redirigez vers l'accueil
            header("Location: ../../../html/pages/accueil-pro.php");

            // Insérer les tarifs publics associés
            foreach ($prices as $price) {
                if (!isset($price['name']) || !isset($price['value'])) {
                    echo "Erreur : données de prix invalides.";
                    continue;
                }

                $age_min = (int)$age;  // Âge minimum par exemple
                $prix_min = is_numeric($price['value']) ? floatval($price['value']) : null;

                var_dump($age_min, $prix_min);  // Afficher les valeurs avant insertion

                $stmtInsertPrice = $dbh->prepare("INSERT INTO sae_db._tarif_public (titre_tarif, prix, offre_id) VALUES (:titre, :prix, :offre_id)");
                $stmtInsertPrice->bindParam(':titre', $price['name']);
                $stmtInsertPrice->bindParam(':prix', $price['value']);
                $stmtInsertPrice->bindParam(':offre_id', $offreId);

                if (!$stmtInsertPrice->execute()) {
                    echo "Erreur lors de l'insertion du prix : " . implode(", ", $stmtInsertPrice->errorInfo());
                }
            }

            echo json_encode(['success' => true]);

            // Gérer les différentes catégories d'offres
            $activity = $_POST['activityType'];
            switch ($activity) {
                case 'activite':
                    // Insertion spécifique à l'activité
                    $stmtActivite = $dbh->prepare("INSERT INTO sae_db._activite (offre_id, est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, idpro, adresse_id, duree_activite, age_requis, prestations) VALUES (:offre_id, true, :description, :resume, :prix, :titre, :date_creation, null, null, null, :adresse_id, :duree, :age, :prestations)");
                    $stmtActivite->bindParam(':offre_id', $offreId);
                    $stmtActivite->bindParam(':description', $description);
                    $stmtActivite->bindParam(':resume', $resume);
                    $stmtActivite->bindParam(':prix', $prixMin);
                    $stmtActivite->bindParam(':date_creation', $dateCreation);
                    $stmtActivite->bindParam(':adresse_id', $adresseId);
                    $stmtActivite->bindParam(':duree', $dureeFormatted);
                    $stmtActivite->bindParam(':age', $age);
                    $stmtActivite->bindParam(':prestations', $prestations);
                    $stmtActivite->bindParam(':titre', $titre);

                    if ($stmtActivite->execute()) {
                        echo "Activité insérée avec succès.";
                        header("location: ../../../html/pages/accueil-pro.php");
                    } else {
                        echo "Erreur lors de l'insertion : " . implode(", ", $stmtActivite->errorInfo());
                    }

                    break;

                    case 'visite':

                    $stmtVisite = $dbh->prepare("INSERT INTO sae_db._visite(offre_id, est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, idpro, adresse_id, duree_visite, guide_visite) VALUES (:offre_id, true, :description, :resume, :prix, :titre, :date_creation, null, null, null, :adresse_id, :duree, false)");
                    $stmtVisite->bindParam(':offre_id', $offreId);
                    $stmtVisite->bindParam(':description', $description);
                    $stmtVisite->bindParam(':resume', $resume);
                    $stmtVisite->bindParam(':prix', $prix);
                    $stmtVisite->bindParam(':date_creation', $dateCreation);
                    $stmtVisite->bindParam(':adresse_id', $adresseId);
                    $stmtVisite->bindParam(':duree', $duree);
                    $stmtVisite->bindParam(':titre', $titre);

                    if ($stmtVisite->execute()) {
                        echo "Visite insérée avec succès.";
                        header("location: ../../../html/pages/accueil-pro.php");
                    } else {
                        echo "Erreur lors de l'insertion : " . implode(", ", $stmtVisite->errorInfo());
                    }

                    break;

                    case 'spectacle':

                        var_dump($capacite);

                    $stmtSpectacle = $dbh->prepare("INSERT INTO sae_db._spectacle(offre_id, est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, idpro, adresse_id, capacite_spectacle, duree_spectacle) VALUES (:offre_id, true, :description, :resume, :prix, :titre, :date_creation, null, null, null, :adresse_id, :capacite, :duree)");
                    $stmtSpectacle->bindParam(':offre_id', $offreId);
                    $stmtSpectacle->bindParam(':description', $description);
                    $stmtSpectacle->bindParam(':resume', $resume);
                    $stmtSpectacle->bindParam(':prix', $prixMin);
                    $stmtSpectacle->bindParam(':date_creation', $dateCreation);
                    $stmtSpectacle->bindParam(':adresse_id', $adresseId);
                    $stmtSpectacle->bindParam(':capacite', $capacite);
                    $stmtSpectacle->bindParam(':duree', $duree);
                    $stmtSpectacle->bindParam(':titre', $titre);

                    if ($stmtSpectacle->execute()) {
                        echo "Spectacle insérée avec succès.";
                        header("location: ../../../html/pages/accueil-pro.php");
                    } else {
                        echo "Erreur lors de l'insertion : " . implode(", ", $stmtSpectacle->errorInfo());
                    }

                    break;

                    case 'parc_attraction':

                    $stmtAttraction = $dbh->prepare("INSERT INTO sae_db._parc_attraction(offre_id, est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, idpro, adresse_id, nb_attractions, age_requis) VALUES (:offre_id, true, :description, :resume, :prix, :titre, :date_creation, null, null, null, :adresse_id, :nb_attraction, :age)");
                    $stmtAttraction->bindParam(':offre_id', $offreId);
                    $stmtAttraction->bindParam(':description', $description);
                    $stmtAttraction->bindParam(':resume', $resume);
                    $stmtAttraction->bindParam(':prix', $prixMin);
                    $stmtAttraction->bindParam(':date_creation', $dateCreation);
                    $stmtAttraction->bindParam(':adresse_id', $adresseId);
                    $stmtAttraction->bindParam(':nb_attraction', $nb_attractions);
                    $stmtAttraction->bindParam(':age', $age);
                    $stmtAttraction->bindParam(':titre', $titre);

                    if ($stmtAttraction->execute()) {
                        echo "Parc d'attraction insérée avec succès.";
                        header("location: ../../../html/pages/accueil-pro.php");
                    } else {
                        echo "Erreur lors de l'insertion : " . implode(", ", $stmtAttraction->errorInfo());
                    }

                    break;

                    case 'restauration':

                    $stmtRestauration = $dbh->prepare("INSERT INTO sae_db._restauration(offre_id, est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, idpro, adresse_id, gamme_prix) VALUES (:offre_id, true, :description, :resume, :prix, :titre, :date_creation, null, null, null, :adresse_id, :gamme_prix)");
                    $stmtRestauration->bindParam(':offre_id', $offreId);
                    $stmtRestauration->bindParam(':description', $description);
                    $stmtRestauration->bindParam(':resume', $resume);
                    $stmtRestauration->bindParam(':prix', $prixMin);
                    $stmtRestauration->bindParam(':date_creation', $dateCreation);
                    $stmtRestauration->bindParam(':adresse_id', $adresseId);
                    $stmtRestauration->bindParam(':gamme_prix', $gamme_prix);
                    $stmtRestauration->bindParam(':titre', $titre);

                    if ($stmtRestauration->execute()) {
                        echo "Restauration insérée avec succès.";
                        header("location: ../../../html/pages/accueil-pro.php");
                    } else {
                        echo "Erreur lors de l'insertion : " . implode(", ", $stmtRestauration->errorInfo());
                    }

                    break;
                
                default:
                    echo "Veuillez sélectionner une activité.";
                    exit;
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Erreur lors de la création de l\'offre : ' . implode(", ", $stmtOffre->errorInfo())]);
        }
    } else {
        echo "Erreur lors de l'insertion dans la table Adresse : " . implode(", ", $stmtAdresseOffre->errorInfo());
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Aucune soumission de formulaire détectée.']);
}
