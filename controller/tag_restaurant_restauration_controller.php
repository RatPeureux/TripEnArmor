<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/tag_restaurant_restauration.php";

class tagRestaurantRestaurationController {
    private $model;

    public function __construct() {
        $this->model = "tagRestaurantRestauration";
    }

    public function getTagsByIdOffre($id_offre) {
        $tags = $this->model::getByIdOffre($id_offre);

        return $tags;
    }

    public function getTagsByIdTag($id_tag) {
        $tags = $this->model::getByIdTagRestaurant($id_tag);
        return $tags;
    }

    public function linkRestaurationAndTag($id_restaurant, $id_tag) {
        if ($this->model::checkIfLinkExists($id_restaurant, $id_tag)) {
            echo "The link already exists<br>";
            return false;
        } else {
            return $this->model::linkOffreAndTag($id_restaurant, $id_tag);
        }
    }

    public function unlinkRestaurationAndTag($id_restaurant, $id_tag)
    {
        if ($this->model::checkIfLinkExists($id_restaurant, $id_tag)) {
            return $this->model::unlinkOffreAndTag($id_restaurant, $id_tag);
        } else {
            echo "The link does not exist";
            return false;
        }
    }

}