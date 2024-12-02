<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/model/avis.php';

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
        $result = $this->model::getAvisByIdMembre($idMembre);
        return $result;
    }

    public function getAvisById($idAvis)
    {
        $result = $this->model::getAvisById($idAvis);
        return $result;
    }

    public function getAvisByIdMembreEtOffre($idMembre, $idOffre)
    {
        $result = $this->model::getAvisByIdMembreEtOffre($idMembre, $idOffre);
        return $result;
    }

    public function createAvis($titre, $date_experience, $id_membre, $id_offre, $note, $contexte_passage, $commentaire = null, $id_avis_reponse = null)
    {
        $resultatSQL = $this->model::createAvis($titre, $date_experience, $id_membre, $id_offre, $note, $contexte_passage, $commentaire, $id_avis_reponse);

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
