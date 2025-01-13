<?php 
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/souscription.php";

class SouscriptionController
{
    private $model;

    function __construct()
    {
        $this->model = 'Souscription';
    }

    public function getSouscriptionById($id_souscription)
    {
        return $this->model::getSouscriptionById($id_souscription);
    }

    public function getAllSouscriptionsByIdOffre($id_offre) {
        return $this->model::getAllSouscriptionsByIdOffre($id_offre);
    }


    public function createSouscription($id_offre, $nom_option, $prix_ht, $prix_ttc, $date_lancement, $nb_semaines)
    {
        return $this->model::createSouscription($id_offre, $nom_option, $prix_ht, $prix_ttc, $date_lancement, $nb_semaines);
    }

    public function updateSpectacle($id_souscription, $value)
    {
        return $this->model::updateSpectacle($id_souscription, $value);
    }
}
