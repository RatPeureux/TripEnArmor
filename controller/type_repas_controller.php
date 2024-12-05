<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/type_repas.php";

class TypeRepasController
{

    private $model;

    function __construct()
    {
        $this->model = 'TypeRepas';
    }

    public function getInfoTypeRepas($id)
    {
        $typeRepas = $this->model::getTypeRepasById($id);

        return $typeRepas;
    }

    public function getTypeRepasByName($name)
    {
        $typeRepas = $this->model::getTypesRepasByName($name);

        if (count($typeRepas) == 0) {
            return false;
        }

        return $typeRepas;
    }

    public function createTypeRepas($nom_type_repas)
    {
        $typeRepasID = $this->model::createTypeRepas($nom_type_repas)[0]['id_type_repas'];

        return $typeRepasID;
    }

    public function updateTypeRepas($id, $nom_type_repas = false)
    {
        if ($nom_type_repas === false) {
            echo "ERREUR: Aucun champ à modifier";
            return -1;
        } else {
            $typeRepas = $this->model::getTypeRepasById($id);

            $updatedTypeRepasId = $this->model::updateTypeRepas(
                $id,
                $nom_type_repas !== false ? $nom_type_repas : $typeRepas["nom_type_repas"]
            );
            return $updatedTypeRepasId;
        }
    }
}