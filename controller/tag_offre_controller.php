<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/model/tag_offre.php';

class TagOffreController
{
    private $model;

    function __construct()
    {
        $this->model = "TagOffre";
    }

    public function getTagsByIdOffre($id_offre)
    {
        $tags = $this->model::getTagsByIdOffre($id_offre);
        return $tags;
    }

    public function getOffresByIdTag($id_tag)
    {
        $tags = $this->model::getOffresByIdTag($id_tag);
        return $tags;
    }

    public function linkOffreAndTag($id_offre, $id_tag)
    {
        if ($this->model::checkIfLinkExists($id_offre, $id_tag)) {
            echo "The link already exists<br>";
            return false;
        } else {
            return $this->model::linkOffreAndTag($id_offre, $id_tag);
        }
    }

    public function unlinkOffreAndTag($id_offre, $id_tag)
    {
        if ($this->model::checkIfLinkExists($id_offre, $id_tag)) {
            return $this->model::unlinkOffreAndTag($id_offre, $id_tag);
        } else {
            echo "The link does not exist";
            return false;
        }
    }
}
