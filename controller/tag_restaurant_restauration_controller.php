<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/tag_restaurant_restauration.php";

class TagRestaurantRestaurationController
{
    private $model;

    public function __construct()
    {
        $this->model = "tagRestaurantRestauration";
    }

    public function getTagsByIdOffre($id_offre)
    {
        $tags = $this->model::getByIdOffre($id_offre);

        $this->model::log("Les tags de l'offre $id_offre ont été lus.");
        return $tags;
    }

    public function getTagsByIdTag($id_tag)
    {
        $tags = $this->model::getByIdTagRestaurant($id_tag);
        $this->model::log("Les tags du restaurant $id_tag ont été lus.");
        return $tags;
    }

    public function linkRestaurationAndTag($id_restaurant, $id_tag)
    {
        if ($this->model::checkIfLinkExists($id_restaurant, $id_tag)) {
            $this->model::log("Le lien entre le restaurant $id_restaurant et le tag $id_tag existe déjà.");
            return false;
        } else {
            $this->model::log("Le lien entre le restaurant $id_restaurant et le tag $id_tag a été créé.");
            return $this->model::linkOffreAndTag($id_restaurant, $id_tag);
        }
    }

    public function unlinkRestaurationAndTag($id_restaurant, $id_tag)
    {
        if ($this->model::checkIfLinkExists($id_restaurant, $id_tag)) {
            $this->model::log("Le lien entre le restaurant $id_restaurant et le tag $id_tag a été supprimé.");
            return $this->model::unlinkOffreAndTag($id_restaurant, $id_tag);
        } else {
            $this->model::log("Le lien entre le restaurant $id_restaurant et le tag $id_tag n'existe pas.");
            return false;
        }
    }

}