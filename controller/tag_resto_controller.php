<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/tag_resto.php";

class TagRestoController
{
    private $model;

    function __construct()
    {
        $this->model = 'TagResto';
    }

    public function getTagResto($id)
    {
        $tag = $this->model::getTagRestoById($id);

        $this->model::log("Les informations du tag $id ont été lues.");
        return $tag;
    }

    public function createTagResto($nom)
    {
        $tag = $this->model::createTagResto($nom);

        $this->model::log("Un tag a été créé.");
        return $tag;
    }

    public function updateTagResto($id, $nom)
    {
        if ($nom === false) {
            $this->model::log("Le tag $id n'a pas été mis à jour.");
            return -1;
        } else {
            $tag = $this->model::getTagRestoById($id);

            $res = $this->model::updateTagResto(
                $id,
                $nom !== false ? $nom : $tag["nom"]
            );

            $this->model::log("Les informations du tag $id ont été mises à jour.");
            return $res;
        }
    }
}