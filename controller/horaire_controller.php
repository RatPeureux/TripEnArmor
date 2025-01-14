<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/horaire.php";

class HoraireController
{

    private $model;

    function __construct()
    {
        $this->model = 'Horaire';
    }

    public function createHoraire($jour, $ouverture, $fermeture, $pause_debut, $pause_fin, $id_offre)
    {
        // TODO: if pause_fin and fermeture null, pause_debut become fermeture

        $horaireID = $this->model::createHoraire($jour, $ouverture == '' ? null : $ouverture, $fermeture == '' ? null : $fermeture, $pause_debut == '' ? null : $pause_debut, $pause_fin == '' ? null : $pause_fin, $id_offre);
        $this->model::log("Un horaire a été créé.");
        return $horaireID;
    }

    public function getHorairesOfOffre($id_offre)
    {
        $horaires = $this->model::getHorairesOfOffre($id_offre);
        array_multisort($horaires);
        $result = [];

        foreach ($horaires as $horaire) {
            $result[$horaire["jour"]] = [
                "id_horaire" => $horaire["id_horaire"],
                "ouverture" => $horaire["ouverture"],
                "fermeture" => $horaire["fermeture"],
                "pause_debut" => $horaire["pause_debut"],
                "pause_fin" => $horaire["pause_fin"]
            ];
        }

        $this->model::log("Les horaires de l'offre $id_offre ont été lus.");
        return $result;
    }

    public function getInfosHoraire($id_horaire)
    {
        $horaire = $this->model::getHoraireById($id_horaire);

        $result = [
            "id_horaire" => $horaire["id_horaire"],
            "ouverture" => $horaire["ouverture"],
            "fermeture" => $horaire["fermeture"],
            "pause_debut" => $horaire["num_pause_debut"],
            "pause_fin" => $horaire["pause_fin"],
            "id_offre" => $horaire["id_offre"]
        ];

        $this->model::log("Les informations de l'horaire $id_horaire ont été lues.");
        return $result;
    }

    public function updateHoraire($id_horaire, $ouverture = false, $fermeture = false, $pause_debut = false, $pause_fin = false, $id_offre = false)
    {
        if ($ouverture === false && $fermeture === false && $pause_debut === false && $pause_fin === false && $id_offre === false) {
            $this->model::log("Aucune information n'a été modifiée.");
            return -1;
        } else {
            $horaire = $this->model::getHoraireById($id_horaire);

            $updatedHoraireId = $this->model::updateHoraire(
                $id_horaire,
                $ouverture !== false ? $ouverture : $horaire["ouverture"],
                $fermeture !== false ? $fermeture : $horaire["fermeture"],
                $pause_debut !== false ? $pause_debut : $horaire["num_pause_debut"],
                $pause_fin !== false ? $pause_fin : $horaire["pause_fin"],
                $id_offre
            );
            $this->model::log("Les informations de l'horaire $id_horaire ont été modifiées.");
            return $updatedHoraireId;
        }
    }
}