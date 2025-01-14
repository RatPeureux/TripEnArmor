<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/facture.php";

class FactureController
{

    private $model;

    function __construct()
    {
        $this->model = 'Facture';
    }

    public function getFactureByNumero($numero_facture)
    {
        return $this->model::getFactureByNumero($numero_facture);
    }

    public function getAllFactures() {
        return $this->model::getAllFactures();
    }

    public function getAllFacturesByIdOffre($id_offre)
    {
        return $this->model::getAllFacturesByIdOffre($id_offre);
    }

    public function createFacture($date_echeance, $date_emission, $id_offre)
    {
        return $this->model::createFacture($date_echeance, $date_emission, $id_offre);
    }

    public function updateFacture($numero_facture, $date_echeance, $date_emission)
    {
        if ($date_echeance === false && $date_emission === false) {
            echo "ERREUR: Aucun champ à modifier";
            return -1;
        } else {
            $facture = $this->model::getFactureByNumero($numero_facture);

            $updatedFacture = $this->model::updateFacture(
                $numero_facture,
                $date_echeance == false ? $facture["date_echeance"] : $date_echeance,
                $date_emission == false ? $facture["date_emission"] : $date_emission,
            );
            return $updatedFacture;
        }
    }
}