<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/tag.php";

class TagController
{

    private $model;

    function __construct()
    {
        $this->model = 'Tag';
    }

    public function getInfosTag($id)
    {
        $tag = $this->model::getTagById($id);

        $result = [
            "id_tag" => $tag["id_tag"],
            "nom" => $tag["nom"]
        ];

        return $result;
    }

    public function getTagsByName($nom, $index = -1)
    {
        $tag = $this->model::getTagByName($nom);

        if ($index == -1) {
            return $tag;
        } else {
            return $tag[$index];
        }
    }

    public function createTag($nom)
    {
        return $this->model::createTag($nom)["id_tag"];
    }
}