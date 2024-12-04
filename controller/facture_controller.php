<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/facture.php";

class FactureController
{

    private $model;

    function __construct()
    {
        $this->model = 'Facture';
    }

    public function getInfoFacture($numero, $designation)
    {
        $facture = $this->model::getFactureById($numero, $designation);

        $result = [
            "id_offre" => $facture["id_offre"],
            "numero" => $facture["numero"],
            "designation" => $facture["designation"],
            "date_emission" => $facture["date_emission"],
            "date_prestation" => $facture["date_prestation"],
            "date_echeance" => $facture["date_echeance"],
            "date_lancement" => $facture["date_lancement"],
            "nbjours_abonnement" => $facture["nbjours_abonnement"],
            "quantite" => $facture["quantite"],
            "prix_unitaire_HT" => isset($facture["prix_unitaire_HT"]) && is_numeric($facture["prix_unitaire_HT"]) ? (float)$facture["prix_unitaire_HT"] : 0,
            "prix_unitaire_TTC" => isset($facture["prix_unitaire_TTC"]) && is_numeric($facture["prix_unitaire_TTC"]) ? (float)$facture["prix_unitaire_TTC"] : 0,
        ];

        return $result;
    }

    public function createFacture($jour_en_ligne, $id_offre)
    {
        $factureID = $this->model::createFacture($jour_en_ligne, $id_offre);
        return $factureID;
    }

    public function updateFacture($numero, $designation, $jour_en_ligne)
    {
        if ($jour_en_ligne === false && $numero === false && $designation === false) {
            echo "ERREUR: Aucun champ à modifier";
            return -1;
        } else {
            // verif de type sur jour en ligne

            if (strtotime($jour_en_ligne)) {
                $facture = $this->model::getFactureById($numero, $designation);

                $updatedFacture = $this->model::updateFacture(
                    $numero,
                    $designation,
                    $facture["jour_en_ligne"],
                    $facture["id_offre"]
                );
                return $updatedFacture;

            } else {
                echo "ERREUR : Format invalide.";
                return -1;
            }


        }
    }
}