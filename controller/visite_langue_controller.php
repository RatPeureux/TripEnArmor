<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/visite_langue.php";
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/controller/langue_controller.php";


class VisiteLangueController
{

    private $model;

    function __construct()
    {
        $this->model = 'VisiteLangue';
    }

    public function getLanguesByIdVisite($id_offre)
    {
        $langues = $this->model::getLanguesBydIdVisite($id_offre);
        $langue_controller = new LangueController();
        $langues = array_map(function ($langue) use ($langue_controller) {
            return $langue_controller->getInfosLangue($langue["id_langue"]);
        }, $langues);

        $this->model::log("Les langues de la visite $id_offre ont été lues.");
        return $langues;
    }

    public function getVisitesByIdLangue($id_langue)
    {
        $visites = $this->model::getVisitesByIdLangue($id_langue);

        $result = [
            "id_offre" => $visites["id_offre"],
        ];

        $this->model::log("Les visites de la langue $id_langue ont été lues.");
        return $result;
    }

    public function linkVisiteAndLangue($id_offre, $id_langue)
    {
        if ($this->model::checkIfLinkExists($id_offre, $id_langue)) {
            $this->model::log("Le lien entre la visite $id_offre et la langue $id_langue existe déjà.");
            return -1;
        } else {
            $this->model::log("Le lien entre la visite $id_offre et la langue $id_langue a été créé.");
            return $this->model::createVisiteLangue($id_offre, $id_langue);
        }
    }

    public function unlinkVisiteAndLangue($id_offre, $id_langue)
    {
        if ($this->model::checkIfLinkExists($id_offre, $id_langue)) {
            $this->model::log("Le lien entre la visite $id_offre et la langue $id_langue a été supprimé.");
            return $this->model::deleteVisiteAndLangue($id_offre, $id_langue);
        } else {
            $this->model::log("Le lien entre la visite $id_offre et la langue $id_langue n'existe pas.");
            return false;
        }
    }
}