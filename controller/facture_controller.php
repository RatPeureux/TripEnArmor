<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/facture.php";

class TypeRepasController
{

    private $model;

    function __construct()
    {
        $this->model = 'Facture';
    }

    public function getInfoFacture($id)
    {
        $facture = $this->model::getFactureById($id);

        $result = [
            "id_facture" => $facture["id_facture"],
            "jour_en_ligne" => $facture["jour_en_ligne"],
            "id_offre" => $facture["id_offre"],
        ];

        return $result;
    }

    public function createFacture($jour_en_ligne, $id_offre)
    {
        $factureID = $this->model::createFacture($jour_en_ligne, $id_offre);
        return $factureID;
    }

    public function updateFacture($id, $jour_en_ligne)
    {
        if ($jour_en_ligne === false && $id === false) {
            echo "ERREUR: Aucun champ à modifier";
            return -1;
        } else {
            // verif de type sur jour en ligne

            if (strtotime($jour_en_ligne)) {
                $facture = $this->model::getFactureById($id);

                $updatedFacture = $this->model::updateFacture(
                    $id,
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