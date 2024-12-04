<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/langue.php";

class LangueController
{

    private $model;

    function __construct()
    {
        $this->model = 'Langue';
    }

    public function getInfosLangue($id)
    {
        $langue = $this->model::getLangueById($id);

        $result = [
            "id_langue" => $langue["id_langue"],
            "nom" => $langue["nom"]
        ];

        return $result;
    }

    public function getInfosLanguesByName($name)
    {
        $langue = $this->model::getLanguesByName($name)[0];

        $result = [
            "id_langue" => $langue["id_langue"],
            "nom" => $langue["nom"]
        ];

        return $result;
    }

    public function getInfosAllLangues() {
        return $this->model::getLangues();
    }
}