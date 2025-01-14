<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/modifier_offre.php";

class ModifierOffreController {

    private $model;

    function __construct() {
        $this->model = 'ModifierOffre';
    }

    public function getOffreById($id) {
        $offre = $this->model::getOffreById($id);
        return $offre;
    }

    public function updateOffre($id, $titre, $description, $resume, $prix_mini, $date_creation, $date_mise_a_jour, $date_suppression, $est_en_ligne, $id_type_offre, $id_pro, $id_adresse, $option, $accessibilite) {
        $updatedOffreId = $this->model::updateOffre($id, $titre, $description, $resume, $prix_mini, $date_creation, $date_mise_a_jour, $date_suppression, $est_en_ligne, $id_type_offre, $id_pro, $id_adresse, $option, $accessibilite);
        return $updatedOffreId;
    }
}