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
        $this->model::log("Les informations de la souscription $id_souscription ont été lues.");
        return $this->model::getSouscriptionById($id_souscription);
    }

    public function getAllSouscriptionsByIdOffre($id_offre)
    {
        $this->model::log("Les informations de la souscription de l'offre $id_offre ont été lues.");
        return $this->model::getAllSouscriptionsByIdOffre($id_offre);
    }


    public function createSouscription($id_offre, $nom_option, $prix_ht, $prix_ttc, $date_lancement, $nb_semaines)
    {
        $this->model::log("Une souscription a été créée pour l'offre $id_offre.");
        return $this->model::createSouscription($id_offre, $nom_option, $prix_ht, $prix_ttc, $date_lancement, $nb_semaines);
    }

    public function updateSpectacle($id_souscription, $value)
    {
        $this->model::log("La souscription $id_souscription a été modifiée.");
        return $this->model::updateSpectacle($id_souscription, $value);
    }
}
