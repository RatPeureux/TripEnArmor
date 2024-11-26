<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/../model/visite_langue.php";

class VisiteLangueController {

    private $model;

    function __construct() {
        $this->model = 'VisiteLangue';
    }
    
    public function createVisiteLangue($id_offre, $id_langue) {
        $visiteLangueID = $this->model::createCompte($id_offre, $id_langue);
        return $visiteLangueID;
    }
    public function getInfosVisiteLangue($id){
        $visiteLangue = $this->model::getVisiteLangueById($id);

        $result = [
            "id_compte" => $visiteLangue["id_compte"],
            "id_langue" => $visiteLangue["id_langue"],
        ];

        return $result;
    }

    public function updateVisiteLangue($id_offre, $id_langue = false) {  
        if ($id_langue === false) {
            echo "ERREUR: Aucun champ à modifier";
            return -1;
        } else {
            $visiteLangue = $this->model::getVisiteLangueById($id_offre);
            
            $updatedVisiteLangueId = $this->model::updateVisiteLangue(
                $id_offre, 
                $id_langue !== false ? $id_langue : $visiteLangue["id_langue"] 
            );
            return $updatedVisiteLangueId;
        }
    }
}