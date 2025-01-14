<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/tarif_public.php";

class TarifPublicController
{

    private $model;

    function __construct()
    {
        $this->model = 'TarifPublic';
    }

    public function getTarifsByIdOffre($id_offre)
    {
        $tarifsPublic = $this->model::getTarifsByIdOffre($id_offre);

        $result = [];
        foreach ($tarifsPublic as $tarifPublic) {
            $result[] = [
                "id_tarif" => $tarifPublic["id_tarif"],
                "titre" => $tarifPublic["titre"],
                "prix" => $tarifPublic["prix"],
            ];
        }

        $this->model::log("Les tarifs de l'offre $id_offre ont été lus.");
        return $result;
    }

    public function getInfoTarifPublic($id)
    {
        $tarifPublic = $this->model::getTarifPublicById($id);

        $result = [
            "titre_tarif" => $tarifPublic["titre_tarif"],
            "prix" => $tarifPublic["prix"],
            "id_offre" => $tarifPublic["id_offre"],
        ];

        $this->model::log("Les informations du tarif $id ont été lues.");
        return $result;
    }

    public function createTarifPublic($titre_tarif, $prix, $id_offre)
    {
        $tarifPublicID = $this->model::createTarifPublic($titre_tarif, $prix, $id_offre);
        $this->model::log("Un tarif public a été créé.");
        return $tarifPublicID;
    }

    public function updateTarifPublic($id, $titre_tarif = false, $prix = false, $id_offre = false)
    {
        if ($titre_tarif === false && $prix === false && $id_offre === false) {
            $this->model::log("Aucune information à mettre à jour.");
            return -1;
        } else {
            $tarifPublic = $this->model::getTarifPublicById($id);

            $updatedTarifPublicId = $this->model::createTarifPublic(
                $id,
                $titre_tarif !== false ? $titre_tarif : $tarifPublic["titre_tarif"],
                $prix !== false ? $prix : $tarifPublic["prix"],
                $id_offre !== false ? $id_offre : $tarifPublic["id_offre"]
            );
            $this->model::log("Les informations du tarif $id ont été mises à jour.");
            return $updatedTarifPublicId;
        }
    }

}