<?php

require dirname($_SERVER['DOCUMENT_ROOT']) . "/model/tag_activite.php";

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

        return $res;
    }
}
