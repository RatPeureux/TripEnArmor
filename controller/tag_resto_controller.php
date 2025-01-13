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

        return $tag;
    }

    public function createTagResto($nom)
    {
        $tag = $this->model::createTagResto($nom);

        return $tag;
    }

    public function updateTagResto($id, $nom)
    {
        if ($nom === false) {
            echo "ERREUR : Aucun champ à modifier";
            return -1;
        } else {
            $tag = $this->model::getTagRestoById($id);

            $res = $this->model::updateTagResto(
                $id,
                $nom !== false ? $nom : $tag["nom"]
            );

            return $res;
        }
    }
}