<?php

require_once dirname(path: $_SERVER['DOCUMENT_ROOT']) . "/model/restauration_type_repas.php";

class RestaurationTypeRepasController
{

    private $model;

    function __construct()
    {
        $this->model = 'RestaurationTypeRepas';
    }

    public function getTypesRepasBydIdRestaurant($id_offre)
    {
        $typesRepas = $this->model::getTypesRepasBydIdRestaurant($id_offre);

        $this->model::log("Les types de repas de l'offre $id_offre ont été lus.");
        return $typesRepas;
    }

    public function getRestaurantsByIdTypeRepas($id_type_repas)
    {
        $restaurants = $this->model::getRestaurantByIdTypesRepas($id_type_repas);

        $result = [
            "id_offre" => $restaurants["id_offre"],
        ];

        $this->model::log("Les restaurants du type de repas $id_type_repas ont été lus.");
        return $result;
    }

    public function linkRestaurantAndTypeRepas($id_offre, $id_type_repas)
    {
        if ($this->model::checkIfLinkExists($id_offre, $id_type_repas)) {
            $this->model::log("Le lien existe déjà.");
            return -1;
        } else {
            $this->model::log("Le lien a été créé.");
            return $this->model::createRestaurantTypeRepas($id_offre, $id_type_repas);
        }
    }

    public function unlinkRestaurantAndTypeRepas($id_offre, $id_type_repas)
    {
        if ($this->model::checkIfLinkExists($id_offre, $id_type_repas)) {
            $this->model::log("The link has been deleted.");
            return $this->model::deleteRestaurantTypeRepas($id_offre, $id_type_repas);
        } else {
            $this->model::log("The link does not exist.");
            return false;
        }
    }
}