<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/tag_activite.php";

class TagActiviteController
{

    private $model;

    function __construct()
    {
        $this->model = 'TagActivite';
    }

    public function getInfosTag($id)
    {
        $tag = $this->model::getTagActiviteById($id);

        $res = [
            "id_offre" => $tag["id_offre"],
            "id_tag" => $tag["id_tag"]
        ];

        $this->model::log("Les informations du tag $id ont été lues.");
        return $res;
    }
}
