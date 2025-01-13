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
        return $offre;
    }
}