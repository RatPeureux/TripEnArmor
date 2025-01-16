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

        $this->model::log("Les informations du type de repas $id ont été lues.");
        return $typeRepasRestaurant;
    }

    public function getTypeRepasRestaurantByName($name)
    {
        $typeRepasRestaurant = $this->model::getTypesRepasRestaurantByName($name);

        if (count($typeRepasRestaurant) == 0) {
            $this->model::log("Les informations du type de repas $name n'ont pas été lues.");
            return false;
        }

        $this->model::log("Les informations du type de repas $name ont été lues.");
        return $typeRepasRestaurant;
    }

    public function createTypeRepasRestaurant($nom_type_repas)
    {
        $typeRepasRestaurantID = $this->model::createTypeRepasRestaurant($nom_type_repas)[0]['id_type_repas'];

        $this->model::log("Un type de repas a été créé.");
        return $typeRepasRestaurantID;
    }

    public function updateTypeRepasRestaurant($id, $nom_type_repas = false)
    {
        if ($nom_type_repas === false) {
            $this->model::log("Le type de repas $id n'a pas été mis à jour.");
            return -1;
        } else {
            $typeRepasRestaurant = $this->model::getTypeRepasRestaurantById($id);

            $updatedTypeRepasRestaurantId = $this->model::updateTypeRepasRestaurant(
                $id,
                $nom_type_repas !== false ? $nom_type_repas : $typeRepasRestaurant["nom_type_repas"]
            );
            $this->model::log("Les informations du type de repas $id ont été mises à jour.");
            return $updatedTypeRepasRestaurantId;
        }
    }
}