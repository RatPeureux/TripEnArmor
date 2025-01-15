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
        $this->model::log("Les tags de l'offre $id_offre ont été lus.");
        return $tags;
    }

    public function getOffresByIdTag($id_tag)
    {
        $tags = $this->model::getOffresByIdTag($id_tag);
        $this->model::log("Les offres du tag $id_tag ont été lues.");
        return $tags;
    }

    public function linkOffreAndTag($id_offre, $id_tag)
    {
        if ($this->model::checkIfLinkExists($id_offre, $id_tag)) {
            $this->model::log("Le lien entre l'offre $id_offre et le tag $id_tag existe déjà.");
            return false;
        } else {
            $this->model::log("Le lien entre l'offre $id_offre et le tag $id_tag a été créé.");
            return $this->model::linkOffreAndTag($id_offre, $id_tag);
        }
    }

    public function unlinkOffreAndTag($id_offre, $id_tag)
    {
        if ($this->model::checkIfLinkExists($id_offre, $id_tag)) {
            $this->model::log("Le lien entre l'offre $id_offre et le tag $id_tag a été supprimé.");
            return $this->model::unlinkOffreAndTag($id_offre, $id_tag);
        } else {
            $this->model::log("Le lien entre l'offre $id_offre et le tag $id_tag n'existe pas.");
            return false;
        }
    }
}
