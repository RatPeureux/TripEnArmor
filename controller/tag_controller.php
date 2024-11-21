<?php

require_once "../model/tag.php";

class TagController {

    private $model;

    function __construct() {
        $this->model = 'Tag';
    }

    public function getInfosTag($id){
        $tag = $this->model::getTagById($id);

        $result = [
            "id_tag" => $tag["id_tag"],
            "nom_tag" => $tag["nom_tag"]
        ];

        return $result;
    }
}