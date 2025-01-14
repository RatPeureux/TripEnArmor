<?php

require_once dirname(path: $_SERVER['DOCUMENT_ROOT']) . "/model/restauration.php";

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

        $this->model::log("Les informations de la restauration $id ont été lues.");
        return $result;
    }

    public function createRestauration($description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $gamme_prix)
    {
        switch ($gamme_prix) {
            case "prix1":
                $gamme_prix = "€";
                break;
            case "prix2":
                $gamme_prix = "€€";
                break;
            case "prix3":
                $gamme_prix = "€€€";
                break;
            default:
                $gamme_prix = "€€";
                break;
        }
        $restauration = $this->model::createRestauration($description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $gamme_prix);

        $this->model::log("Une restauration a été créée.");
        return $restauration;
    }

    public function updateRestauration($id, $est_en_ligne, $description = false, $resume = false, $prix_mini = false, $titre = false, $id_pro = false, $id_type_offre = false, $id_adresse = false, $gamme_prix = false, $id_type_repas = false)
    {
        if ($description === false && $resume === false && $prix_mini === false && $titre === false && $id_pro === false && $id_type_offre === false && $id_adresse === false && $gamme_prix === false && $id_type_repas === false) {
            $this->model::log("Aucune information n'a été modifiée.");
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

            $this->model::log("Les informations de la restauration $id ont été modifiées.");
            return $res;
        }
    }

    public function deleteRestauration($id)
    {
        $restauration = $this->model::deleteRestauration($id);

        $this->model::log("La restauration $id a été supprimée.");
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

        $this->model::log("Les informations de la restauration $id ont été modifiées.");
        return $res;
    }
}