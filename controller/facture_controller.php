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
        $this->model::log("Les informations de la facture $numero_facture ont été lues.");
        return $this->model::getFactureByNumero($numero_facture);
    }

    public function getAllFactures()
    {
        $this->model::log("Toutes les factures ont été lues.");
        return $this->model::getAllFactures();
    }

    public function getAllFacturesByIdOffre($id_offre)
    {
        return $this->model::getAllFacturesByIdOffre($id_offre);
    }

    public function createFacture($date_echeance, $date_emission, $id_offre)
    {
        $this->model::log("Une facture a été créée.");
        return $this->model::createFacture($date_echeance, $date_emission, $id_offre);
    }

    public function updateFacture($numero_facture, $date_echeance, $date_emission)
    {
        if ($date_echeance === false && $date_emission === false) {
            $this->model::log("Aucune information n'a été modifiée.");
            return -1;
        } else {
            $facture = $this->model::getFactureByNumero($numero_facture);

            $updatedFacture = $this->model::updateFacture(
                $numero_facture,
                $date_echeance == false ? $facture["date_echeance"] : $date_echeance,
                $date_emission == false ? $facture["date_emission"] : $date_emission,
            );
            $this->model::log("Les informations de la facture $numero_facture ont été modifiées.");
            return $updatedFacture;
        }
    }
}