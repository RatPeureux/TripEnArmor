<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/type_repas_restaurant.php";

class TypeRepasRestaurantController
{

    private $model;

    function __construct()
    {
        $this->model = 'TypeRepasRestaurant';
    }

    public function getTypeRepasRestaurant($id)
    {
        $typeRepasRestaurant = $this->model::getTypeRepasRestaurantById($id);

        return $typeRepasRestaurant;
    }

    public function getTypeRepasRestaurantByName($name)
    {
        $typeRepasRestaurant = $this->model::getTypesRepasRestaurantByName($name);

        if (count($typeRepasRestaurant) == 0) {
            return false;
        }

        return $typeRepasRestaurant;
    }

    public function createTypeRepasRestaurant($nom_type_repas)
    {
        $typeRepasRestaurantID = $this->model::createTypeRepasRestaurant($nom_type_repas)[0]['id_type_repas_restaurant'];

        return $typeRepasRestaurantID;
    }

    public function updateTypeRepasRestaurant($id, $nom_type_repas = false)
    {
        if ($nom_type_repas === false) {
            echo "ERREUR: Aucun champ à modifier";
            return -1;
        } else {
            $typeRepasRestaurant = $this->model::getTypeRepasRestaurantById($id);

            $updatedTypeRepasRestaurantId = $this->model::updateTypeRepasRestaurant(
                $id,
                $nom_type_repas !== false ? $nom_type_repas : $typeRepasRestaurant["nom_type_repas"]
            );
            return $updatedTypeRepasRestaurantId;
        }
    }
}