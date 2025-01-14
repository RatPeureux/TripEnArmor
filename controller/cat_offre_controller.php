<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/cat_offre.php";

class CatOffreController
{
    private $model;

    function __construct()
    {
        $this->model = 'CatOffre';
    }

    public function getOffreCategorie($id_cat_offre)
    {
        $offre = $this->model::getOffreCategorie($id_cat_offre);
        $this->model::log("Les offres de la catégorie $id_cat_offre ont été lues.");
        return $offre;
    }
}