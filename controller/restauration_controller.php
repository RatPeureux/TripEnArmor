<?php

require dirname(path: $_SERVER['DOCUMENT_ROOT']) . "/model/restauration.php";

class RestaurationController
{
    private $model;

    function __construct()
    {
        $this->model = 'Restauration';
    }

    public function getInfosRestauration($id)
    {
        $result = $this->model::getRestaurationById($id);
        return $result;
    }

    public function createRestauration($description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $gamme_prix, $id_type_repas)
    {
        $restauration = $this->model::createActivite($description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $gamme_prix, $id_type_repas);

        return $restauration;
    }

    public function updateRestauration($id, $est_en_ligne, $description = false, $resume = false, $prix_mini = false, $titre = false, $id_pro = false, $id_type_offre = false, $id_adresse = false, $gamme_prix = false, $id_type_repas = false)
    {
        if ($description === false && $resume === false && $prix_mini === false && $titre === false && $id_pro === false && $id_type_offre === false && $id_adresse === false && $gamme_prix === false && $id_type_repas === false) {
            echo "ERREUR : Aucun champ à modifier";
            return -1;
        } else {
            $restauration = $this->model::getRestaurationById($id);

            $res = $this->model::updateRestauration(
                $id,
                $est_en_ligne,
                $description !== false ? $description : $restauration["description"],
                $resume !== false ? $resume : $restauration["resume"],
                $prix_mini !== false ? $prix_mini : $restauration["prix_mini"],
                $titre !== false ? $titre : $restauration["titre"],
                $id_pro !== false ? $id_pro : $restauration['id_pro'],
                $id_type_offre !== false ? $id_type_offre : $restauration["id_type_offre"],
                $id_adresse !== false ? $id_adresse : $restauration["id_adresse"],
                $gamme_prix !== false ? $gamme_prix : $restauration["gamme_prix"],
                $id_type_repas !== false ? $id_type_repas : $restauration["id_type_repas"]
            );

            return $res;
        }
    }

    public function deleteRestauration($id)
    {
        $restauration = $this->model::deleteRestauration($id);

        return $restauration;
    }

    public function toggleOnline($id)
    {
        $restauration = $this->model::getRestaurationById($id);

        $res = $this->model::updateRestauration(
            $id,
            !$restauration["est_en_ligne"],
            $restauration["description"],
            $restauration["resume"],
            $restauration["prix_mini"],
            $restauration["titre"],
            $restauration['id_pro'],
            $restauration["id_type_offre"],
            $restauration["id_adresse"],
            $restauration["gamme_prix"],
            $restauration["id_type_repas"]
        );

        return $res;
    }
}