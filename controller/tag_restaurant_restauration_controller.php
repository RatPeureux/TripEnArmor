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

    public function linkRestaurationAndTag($id_restaurant, $id_tag) {}

}