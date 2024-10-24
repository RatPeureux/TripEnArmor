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
        $numero = substr($adresse, 0, 1);  // À adapter selon le format de l'adresse TODO R2CUPERER SELON PERMIER ESPACE
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
                    $stmtActivity = $dbh->prepare("INSERT INTO sae_db._activite (offre_id, est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, idpro, adresse_id, duree_activite, age_requis, prestations) VALUES (:offre_id, true, :description, :resume, :prix, :titre, :date_creation, null, null, null, :adresse_id, :duree, :age, :prestations)");
                    $stmtActivity->bindParam(':offre_id', $offreId);
                    $stmtActivity->bindParam(':description', $description);
                    $stmtActivity->bindParam(':resume', $resume);
                    $stmtActivity->bindParam(':prix', $prixMin);
                    $stmtActivity->bindParam(':date_creation', $dateCreation);
                    $stmtActivity->bindParam(':adresse_id', $adresseId);
                    $stmtActivity->bindParam(':duree', $dureeFormatted);
                    $stmtActivity->bindParam(':age', $age);
                    $stmtActivity->bindParam(':prestations', $prestations);
                    $stmtActivity->bindParam(':titre', $titre);

                    break;

                case 'visite':

                    $stmtActivity = $dbh->prepare("INSERT INTO sae_db._visite(offre_id, est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, idpro, adresse_id, duree_visite, guide_visite) VALUES (:offre_id, true, :description, :resume, :prix, :titre, :date_creation, null, null, null, :adresse_id, :duree, false)");
                    $stmtActivity->bindParam(':offre_id', $offreId);
                    $stmtActivity->bindParam(':description', $description);
                    $stmtActivity->bindParam(':resume', $resume);
                    $stmtActivity->bindParam(':prix', $prix);
                    $stmtActivity->bindParam(':date_creation', $dateCreation);
                    $stmtActivity->bindParam(':adresse_id', $adresseId);
                    $stmtActivity->bindParam(':duree', $duree);
                    $stmtActivity->bindParam(':titre', $titre);

                    break;

                case 'spectacle':

                    var_dump($capacite);

                    $stmtActivity = $dbh->prepare("INSERT INTO sae_db._spectacle(offre_id, est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, idpro, adresse_id, capacite_spectacle, duree_spectacle) VALUES (:offre_id, true, :description, :resume, :prix, :titre, :date_creation, null, null, null, :adresse_id, :capacite, :duree)");
                    $stmtActivity->bindParam(':offre_id', $offreId);
                    $stmtActivity->bindParam(':description', $description);
                    $stmtActivity->bindParam(':resume', $resume);
                    $stmtActivity->bindParam(':prix', $prixMin);
                    $stmtActivity->bindParam(':date_creation', $dateCreation);
                    $stmtActivity->bindParam(':adresse_id', $adresseId);
                    $stmtActivity->bindParam(':capacite', $capacite);
                    $stmtActivity->bindParam(':duree', $duree);
                    $stmtActivity->bindParam(':titre', $titre);

                    break;

                case 'parc_attraction':

                    $stmtActivity = $dbh->prepare("INSERT INTO sae_db._parc_attraction(offre_id, est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, idpro, adresse_id, nb_attractions, age_requis) VALUES (:offre_id, true, :description, :resume, :prix, :titre, :date_creation, null, null, null, :adresse_id, :nb_attraction, :age)");
                    $stmtActivity->bindParam(':offre_id', $offreId);
                    $stmtActivity->bindParam(':description', $description);
                    $stmtActivity->bindParam(':resume', $resume);
                    $stmtActivity->bindParam(':prix', $prixMin);
                    $stmtActivity->bindParam(':date_creation', $dateCreation);
                    $stmtActivity->bindParam(':adresse_id', $adresseId);
                    $stmtActivity->bindParam(':nb_attraction', $nb_attractions);
                    $stmtActivity->bindParam(':age', $age);
                    $stmtActivity->bindParam(':titre', $titre);

                    break;

                case 'restauration':

                    $stmtActivity = $dbh->prepare("INSERT INTO sae_db._restauration(offre_id, est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, idpro, adresse_id, gamme_prix) VALUES (:offre_id, true, :description, :resume, :prix, :titre, :date_creation, null, null, null, :adresse_id, :gamme_prix)");
                    $stmtActivity->bindParam(':offre_id', $offreId);
                    $stmtActivity->bindParam(':description', $description);
                    $stmtActivity->bindParam(':resume', $resume);
                    $stmtActivity->bindParam(':prix', $prixMin);
                    $stmtActivity->bindParam(':date_creation', $dateCreation);
                    $stmtActivity->bindParam(':adresse_id', $adresseId);
                    $stmtActivity->bindParam(':gamme_prix', $gamme_prix);
                    $stmtActivity->bindParam(':titre', $titre);

                    break;
                
                default:
                    echo "Veuillez sélectionner une activité.";
                    exit;
            }

            if ($stmtActivity && $stmtActivity->execute()) {
                echo "Acitivité insérée avec succès.";
                header("location: ../../../html/pages/accueil-pro.php");

                // Gérer les fichiers images soumis
                if (isset($_FILES)) {
                    $uploadDir = '../../public/uploads/';
                    foreach ($_FILES['photo-upload-carte']['tmp_name'] as $key => $tmpName) {
                        $fileName = basename($_FILES['images']['name'][$key]);
                        $targetFilePath = $uploadDir . $fileName;

                        // Vérifier si le fichier est une image
                        $check = getimagesize($tmpName);
                        if ($check !== false) {
                            if (move_uploaded_file($tmpName, $targetFilePath)) {
                                // Insérer le chemin de l'image dans la base de données
                                $stmtImage = $dbh->prepare("INSERT INTO sae_db.T_Image_Img (offre_id, img_path) VALUES (:offre_id, :file_path)");
                                $stmtImage->bindParam(':offre_id', $offreId);
                                $stmtImage->bindParam(':file_path', $targetFilePath);

                                if (!$stmtImage->execute()) {
                                    echo "Erreur lors de l'insertion de l'image : " . implode(", ", $stmtImage->errorInfo());
                                }
                            } else {
                                echo "Erreur lors du téléchargement de l'image.";
                            }
                        } else {
                            echo "Le fichier n'est pas une image.";
                        }
                    }
                }
            } else {
                echo "Erreur lors de l'insertion : " . implode(", ", $stmtActivity->errorInfo());
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
