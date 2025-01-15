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
        $this->model::log("Les périodes en ligne de l'offre $id_offre ont été lues.");
        return $this->model::getAllPeriodesEnLigneByIdOffre($id_offre);
    }

    public function createPeriodeEnLigne($id_offre, $type_offre, $prix_ht, $prix_ttc)
    {
        $this->model::log("Une période en ligne a été créée pour l'offre $id_offre.");
        return $this->model::createPeriodeEnLigne($id_offre, $type_offre, $prix_ht, $prix_ttc);
    }

    public function clorePeriodeByIdOffre($id_offre)
    {
        $this->model::log("La période en ligne de l'offre $id_offre a été clôturée.");
        return $this->model::clorePeriodeByIdOffre($id_offre);
    }

    public function ouvrirPeriodeByIdOffre($id_offre)
    {
        $this->model::log("La période en ligne de l'offre $id_offre a été ouverte.");
        return $this->model::ouvrirPeriodeByIdOffre($id_offre);
    }

    public function getLastDateFinByIdOffre($id_offre)
    {
        $this->model::log("La date de fin de la période en ligne de l'offre $id_offre a été lue.");
        return $this->model::getLastDateFinByIdOffre($id_offre);
    }
}
