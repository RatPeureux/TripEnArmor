<?php
include('/php/connect_params.php');

$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Partie pour traiter la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assurer que tous les champs obligatoires sont remplis
    $adresse = $_POST['adresse'];
    $code = $_POST['code'];
    $ville = $_POST['ville'];
    $description = $_POST['description'];
    $resume = $_POST['resume'];
    $titre = $_POST['titre'];
    $age = $_POST['age'];
    $prix = $_POST['prix'];
    $duree = $_POST['duree'];
    $prestations = $_POST['newPrestationName'];
    $capacite = $_POST['place'];
    $nb_attractions = $_POST['parc-numb'];
    $gamme_prix = $_POST['gamme_prix'];

    // Vérifiez que tous les champs obligatoires sont remplis
    if ($adresse && $code && $ville && $description && $resume && $titre && $age) {
        // Insérer dans la base de données pour l'adresse
        $stmtAdresseOffre = $dbh->prepare("INSERT INTO sae_db._adresse (adresse_postale, code_postal, ville) VALUES (:adresse, :code, :ville)");
        $stmtAdresseOffre->bindParam(':ville', $ville);
        $stmtAdresseOffre->bindParam(':adresse', $adresse);
        $stmtAdresseOffre->bindParam(':code', $code);

        if ($stmtAdresseOffre->execute()) {
            $adresseId = $dbh->lastInsertId();
            $dateCreation = date('Y-m-d H:i:s');

            // Préparer l'insertion dans la table Offre
            $stmtOffre = $dbh->prepare("INSERT INTO sae_db._offre (est_en_ligne, description_offre, resume_offre, prix_mini, date_creation, date_mise_a_jour, date_suppression, adresse_id) VALUES (true, :description, :resume, :prix, :date_creation, null, null, :adresse_id)");
            $stmtOffre->bindParam(':description', $description);
            $stmtOffre->bindParam(':resume', $resume);
            $stmtOffre->bindParam(':adresse_id', $adresseId);
            $stmtOffre->bindParam(':date_creation', $dateCreation);
            $stmtOffre->bindParam(':prix', $prix);

            if ($stmtOffre->execute()) {
                $offreId = $dbh->lastInsertId();

                if ($stmtOffre->execute()) {
                    // Insérer dans la table Tarif_public
                    try {
                        $stmtTarifPublic = $dbh->prepare("INSERT INTO sae_db._tarif_public (titre_tarif, age_min, age_max, offre_id) VALUES (:titre, :age_min, :age_max, :offre_id)");
                        $stmtTarifPublic->bindParam(':titre', $titre);
                        $stmtTarifPublic->bindParam(':age_min', $age);
                        $stmtTarifPublic->bindParam(':age_max', $age);
                        $stmtTarifPublic->bindParam(':offre_id', $offreId);

                        if ($stmtTarifPublic->execute()) {
                            echo json_encode(['success' => true]);
                            
                            $activity = $_POST['activityType'];

                            switch ($activity) {
                                case 'activite':

                                    $stmtActivite = $dbh->prepare("INSERT INTO sae_db._activite(offre_id, est_en_ligne, description_offre, resume_offre, prix_mini, date_creation, date_mise_a_jour, date_suppression, idpro, adresse_id, duree_activite, age_requis, prestations) VALUES (:offre_id, true, :description, :resume, :prix, :date_creation, null, null, null, :adresse_id, :duree, :age, :prestations)");
                                    $stmtActivite->bindParam(':offre_id', $offreId);
                                    $stmtActivite->bindParam(':description', $description);
                                    $stmtActivite->bindParam(':resume', $resume);
                                    $stmtActivite->bindParam(':prix', $prix);
                                    $stmtActivite->bindParam(':date_creation', $dateCreation);
                                    $stmtActivite->bindParam(':adresse_id', $adresseId);
                                    $stmtActivite->bindParam(':duree', $duree);
                                    $stmtActivite->bindParam(':age', $age);
                                    $stmtActivite->bindParam(':prestations', $prestations);

                                    if ($stmtActivite->execute()) {
                                        echo "Activité insérée avec succès.";
                                        header("location: /pages/accueil-pro.php");
                                    } else {
                                        echo "Erreur lors de l'insertion : " . implode(", ", $stmtActivite->errorInfo());
                                    }

                                    break;

                                    case 'visite':

                                    $stmtVisite = $dbh->prepare("INSERT INTO sae_db._visite(offre_id, est_en_ligne, description_offre, resume_offre, prix_mini, date_creation, date_mise_a_jour, date_suppression, idpro, adresse_id, duree_visite, guide_visite) VALUES (:offre_id, true, :description, :resume, :prix, :date_creation, null, null, null, :adresse_id, :duree, false)");
                                    $stmtVisite->bindParam(':offre_id', $offreId);
                                    $stmtVisite->bindParam(':description', $description);
                                    $stmtVisite->bindParam(':resume', $resume);
                                    $stmtVisite->bindParam(':prix', $prix);
                                    $stmtVisite->bindParam(':date_creation', $dateCreation);
                                    $stmtVisite->bindParam(':adresse_id', $adresseId);
                                    $stmtVisite->bindParam(':duree', $duree);

                                    if ($stmtVisite->execute()) {
                                        echo "Visite insérée avec succès.";
                                        header("location: /pages/accueil-pro.php");
                                    } else {
                                        echo "Erreur lors de l'insertion : " . implode(", ", $stmtVisite->errorInfo());
                                    }

                                    break;

                                    case 'spectacle':

                                    $stmtSpectacle = $dbh->prepare("INSERT INTO sae_db._spectacle(offre_id, est_en_ligne, description_offre, resume_offre, prix_mini, date_creation, date_mise_a_jour, date_suppression, idpro, adresse_id, capacite_spectacle, duree_spectacle) VALUES (:offre_id, true, :description, :resume, :prix, :date_creation, null, null, null, :adresse_id, :capacite, :duree)");
                                    $stmtSpectacle->bindParam(':offre_id', $offreId);
                                    $stmtSpectacle->bindParam(':description', $description);
                                    $stmtSpectacle->bindParam(':resume', $resume);
                                    $stmtSpectacle->bindParam(':prix', $prix);
                                    $stmtSpectacle->bindParam(':date_creation', $dateCreation);
                                    $stmtSpectacle->bindParam(':adresse_id', $adresseId);
                                    $stmtSpectacle->bindParam(':capacite', $capacite);
                                    $stmtSpectacle->bindParam(':duree', $duree);

                                    if ($stmtSpectacle->execute()) {
                                        echo "Spectacle insérée avec succès.";
                                        header("location: /pages/accueil-pro.php");
                                    } else {
                                        echo "Erreur lors de l'insertion : " . implode(", ", $stmtSpectacle->errorInfo());
                                    }

                                    break;

                                    case 'parc_attraction':

                                    $stmtAttraction = $dbh->prepare("INSERT INTO sae_db._parc_attraction(offre_id, est_en_ligne, description_offre, resume_offre, prix_mini, date_creation, date_mise_a_jour, date_suppression, idpro, adresse_id, nb_attraction, age_requis) VALUES (:offre_id, true, :description, :resume, :prix, :date_creation, null, null, null, :adresse_id, :duree, :nb_attraction, :age)");
                                    $stmtAttraction->bindParam(':offre_id', $offreId);
                                    $stmtAttraction->bindParam(':description', $description);
                                    $stmtAttraction->bindParam(':resume', $resume);
                                    $stmtAttraction->bindParam(':prix', $prix);
                                    $stmtAttraction->bindParam(':date_creation', $dateCreation);
                                    $stmtAttraction->bindParam(':adresse_id', $adresseId);
                                    $stmtAttraction->bindParam(':duree', $duree);
                                    $stmtAttraction->bindParam(':nb_attractiob', $nb_attractions);
                                    $stmtAttraction->bindParam(':age', $age);

                                    if ($stmtAttraction->execute()) {
                                        echo "Parc d'attraction insérée avec succès.";
                                        header("location: /pages/accueil-pro.php");
                                    } else {
                                        echo "Erreur lors de l'insertion : " . implode(", ", $stmtAttraction->errorInfo());
                                    }

                                    break;

                                    case 'restauration':

                                    $stmtRestauration = $dbh->prepare("INSERT INTO sae_db._restauration(offre_id, est_en_ligne, description_offre, resume_offre, prix_mini, date_creation, date_mise_a_jour, date_suppression, idpro, adresse_id, gamme_prix) VALUES (:offre_id, true, :description, :resume, :prix, :date_creation, null, null, null, :adresse_id, :gamme_prix)");
                                    $stmtActivite->bindParam(':offre_id', $offreId);
                                    $stmtActivite->bindParam(':description', $description);
                                    $stmtActivite->bindParam(':resume', $resume);
                                    $stmtActivite->bindParam(':prix', $prix);
                                    $stmtActivite->bindParam(':date_creation', $dateCreation);
                                    $stmtActivite->bindParam(':adresse_id', $adresseId);
                                    $stmtActivite->bindParam(':gamme_prix', $gamme_prix);

                                    if ($stmtRestauration->execute()) {
                                        echo "Restauration insérée avec succès.";
                                        header("location: /pages/accueil-pro.php");
                                    } else {
                                        echo "Erreur lors de l'insertion : " . implode(", ", $stmtRestauration->errorInfo());
                                    }

                                    break;
                                
                                default:
                                    echo "Veuillez sélectionner une activité.";
                                    exit;
                            }
                            
                        } else {
                            echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'insertion dans la table Tarif_public : ' . implode(", ", $stmtTarifPublic->errorInfo())]);
                        }
                    } catch (Exception $e) {
                        echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'insertion dans la table Tarif_public : ' . $e->getMessage()]);
                    }
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Erreur lors de la création de l\'offre : ' . implode(", ", $stmtOffre->errorInfo())]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'insertion dans la table Adresse : ' . implode(", ", $stmtAdresseOffre->errorInfo())]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Tous les champs obligatoires doivent être remplis.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Aucune soumission de formulaire détectée.']);
}
?>
