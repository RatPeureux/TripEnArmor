<?php

require_once "../model/restauration_type_repas.php";

class VisiteLangueController {

    private $model;

    function __construct() {
        $this->model = 'RestaurationTypeRepas';
    }
    
    public function getTypesRepasBydIdRestaurant($id_offre){
        $typesRepas = $this->model::getTypesRepasBydIdRestaurant($id_offre);

        $result = [
            "id_type_repas" => $typesRepas["id_type_repas"],
        ];

        return $result;
    }

    public function getRestaurantsByIdTypeRepas($id_type_repas) {
        $restaurants = $this->model::getRestaurantByIdTypesRepas($id_type_repas);

        $result = [
            "id_offre" => $restaurants["id_offre"],
        ];

        return $result;
    }

    public function linkRestaurantAndTypeRepas($id_offre, $id_type_repas) {
        if ($this->model::checkIfLinkExists($id_offre, $id_type_repas)) {
            return $this->model::createRestaurantTypeRepas($id_offre, $id_type_repas);
        } else {
            echo "The link already exists";
            return -1;
        }
    }

    public function unlinkRestaurantAndTypeRepas($id_offre, $id_type_repas) {
        if ($this->model::checkIfLinkExists($id_offre, $id_type_repas)) {
            return $this->model::deleteRestaurantTypeRepas($id_offre, $id_type_repas);
        } else {
            echo "The link does not exist";
            return false;
        }
    }
}