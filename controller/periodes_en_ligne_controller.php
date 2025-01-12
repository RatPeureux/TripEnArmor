<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/periodes_en_ligne.php";

class PeriodesEnLigneController
{
    private $model;

    function __construct()
    {
        $this->model = 'PeriodesEnLigne';
    }

    public function getAllPeriodesEnLigneByIdOffre($id_offre)
    {
        return $this->model::getAllPeriodesEnLigneByIdOffre($id_offre);
    }

    public function createPeriodeEnLigne($id_offre, $type_offre, $prix_ht)
    {
        return $this->model::createPeriodeEnLigne($id_offre, $type_offre, $prix_ht);
    }

    public function clorePeriodeByIdOffre($id_offre)
    {
        return $this->model::clorePeriodeByIdOffre($id_offre);
    }

    public function ouvrirPeriodeByIdOffre($id_offre) {
        return $this->model::ouvrirPeriodeByIdOffre($id_offre);
    }

    public function getLastDateFinByIdOffre($id_offre) {
        return $this->model::getLastDateFinByIdOffre($id_offre);
    }
}
