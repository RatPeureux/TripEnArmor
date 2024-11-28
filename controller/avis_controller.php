<?php
require dirname($_SERVER['DOCUMENT_ROOT']) . '/model/avis.php';

class AvisController
{
    private $model = "Avis";

    public function getAvisByIdOffre($idOffre)
    {
        $result = $this->model::getAvisByIdOffre($idOffre);

        if ($result) {
            return $result;
        } else {
            return -1;
        }
    }

    public function getAvisByIdMembre($idMembre)
    {
        $resultatSQL = $this->model::getAvisByIdMembre($idMembre);

        if ($resultatSQL) {
            $result = array_map(function ($row) {
                return [
                    "id_avis" => $row["id_avis"],
                    "titre" => $row["titre"],
                    "commentaire" => $row["commentaire"],
                    "date_experience" => $row["date_experience"],
                    "date_publication" => $row["date_publication"],
                    "id_avis_reponse" => $row["id_avis_reponse"],
                ];
            }, $resultatSQL);
        } else {
            echo "ERREUR: Aucun avis trouvé";
            return -1;
        }
    }

    public function createAvis($titre, $commentaire, $date_experience, $id_compte, $id_offre, $id_avis_reponse = null)
    {
        $resultatSQL = $this->model::createAvis($titre, $commentaire, $date_experience, $id_compte, $id_offre, $id_avis_reponse = null);


        if ($resultatSQL) {
            return $resultatSQL;
        } else {
            echo "ERREUR: Impossible de créer l'avis";
            return -1;
        }
    }

    public function createReponsePro($id_avis, $commentaire, $id_pro)
    {
        $avisInitial = $this->model::getAvisById($id_avis);
        if (!$avisInitial) {
            echo "ERREUR: Avis inexistant";
            return -1;
        }

        $resultatSQL = $this->model::createAvis(
            $avisInitial["titre"],
            $commentaire,
            $avisInitial["date_experience"],
            $id_pro,
            $avisInitial["id_offre"],
            $id_avis
        );

        if ($resultatSQL) {
            return $resultatSQL;
        } else {
            echo "ERREUR: Impossible de créer la réponse";
            return -1;
        }
    }

    public function updateAvis($id, $titre = false, $commentaire = false, $date_experience = false, $id_pro = false, $id_offre = false, $id_avis_reponse = false)
    {
        if ($titre === false && $commentaire === false && $date_experience === false && $id_pro === false && $id_offre === false && $id_avis_reponse === false) {
            echo "ERREUR: Aucun champ à modifier";
            return -1;
        } else {
            $avis = $this->model::getAvisById($id);

            $updated_id_avis = $this->model::updateAvis(
                $id,
                $titre !== false ? $titre : $avis["titre"],
                $commentaire !== false ? $commentaire : $avis["commentaire"],
                $date_experience !== false ? $date_experience : $avis["date_experience"],
                $id_pro !== false ? $id_pro : $avis["id_pro"],
                $id_offre !== false ? $id_offre : $avis["id_offre"],
                $id_avis_reponse !== false ? $id_avis_reponse : $avis["id_avis_reponse"]
            );

            if ($updated_id_avis !== false) {
                return $updated_id_avis;
            } else {
                echo "ERREUR: Impossible de mettre à jour l'avis";
                return -1;
            }
        }
    }

    public function deleteAvis($id)
    {
        if ($this->model::deleteAvis($id)) {
            echo "Avis supprimé";
            return 0;
        } else {
            echo "ERREUR: Impossible de supprimer l'avis";
            return -1;
        }
    }
}
