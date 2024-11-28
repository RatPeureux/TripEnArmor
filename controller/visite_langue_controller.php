<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/visite_langue.php";
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/controller/langue_controller.php";


class VisiteLangueController {

    private $model;

    function __construct() {
        $this->model = 'VisiteLangue';
    }
    
    public function getLanguesByIdVisite($id_offre){
        $langues = $this->model::getLanguesBydIdVisite($id_offre);
        $langue_controller = new LangueController();
        $langues = array_map(function($langue) use ($langue_controller) {
            return $langue_controller->getInfosLangue($langue["id_langue"]);
        }, $langues);

        return $langues;
    }

    public function getVisitesByIdLangue($id_langue) {
        $visites = $this->model::getVisitesByIdLangue($id_langue);

        $result = [
            "id_offre" => $visites["id_offre"],
        ];

        return $result;
    }

    public function linkVisiteAndLangue($id_offre, $id_langue) {
        if ($this->model::checkIfLinkExists($id_offre, $id_langue)) {
            return $this->model::createVisiteLangue($id_offre, $id_langue);
        } else {
            echo "The link already exists";
            return -1;
        }
    }

    public function unlinkVisiteAndLangue($id_offre, $id_langue) {
        if ($this->model::checkIfLinkExists($id_offre, $id_langue)) {
            return $this->model::deleteVisiteAndLangue($id_offre, $id_langue);
        } else {
            echo "The link does not exist";
            return false;
        }
    }
}