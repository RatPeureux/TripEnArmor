<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/tag_restaurant.php";

class TagRestaurantController
{
    private $model;

    public function __construct()
    {
        $this->model = "TagRestaurant";
    }

    public function getInfosTagRestaurant($id)
    {
        $tag = $this->model::getTagRestaurantById($id);

        $this->model::log("Les informations du tag $id ont été lues.");
        return $tag;
    }

    public function getTagsRestaurantByName($name)
    {
        $tags = $this->model::getTagsRestaurantByName($name);

        if (count($tags) == 0) {
            $this->model::log("Les informations du tag $name n'ont pas été lues.");
            return false;
        }

        $this->model::log("Les informations du tag $name ont été lues.");
        return $tags;
    }

    public function createTag($name)
    {
        $this->model::log("Un tag a été créé.");
        return $this->model::createTagRestaurant($name)[0]['id_tag_restaurant'];
    }
}