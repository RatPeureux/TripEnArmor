<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/model/avis.php';

class AvisController
{
    private $model = "Avis";

    public function getAvisByIdOffre($idOffre)
    {
        $result = $this->model::getAvisByIdOffre($idOffre);

        if ($result) {
            $this->model::log("Les avis de l'offre $idOffre ont été lus.");
            return $result;
        } else {
            $this->model::log("Aucun avis trouvé pour l'offre $idOffre.");
            return -1;
        }
    }

    public function getAvisByIdMembre($idMembre)
    {
        $this->model::log("Les avis du membre $idMembre ont été lus.");
        return $this->model::getAvisByIdMembre($idMembre);
    }

    public function getAvisByIdPro($id_pro)
    {
        $this->model::log("Les avis du pro $id_pro ont été lus.");
        return $this->model::getAvisByIdPro($id_pro);
    }

    public function getAvisById($idAvis)
    {
        $result = $this->model::getAvisById($idAvis);
        $this->model::log("L'avis $idAvis a été lu.");
        return $result;
    }

    public function getAvisByIdMembreEtOffre($idMembre, $idOffre)
    {
        $result = $this->model::getAvisByIdMembreEtOffre($idMembre, $idOffre);
        $this->model::log("L'avis du membre $idMembre pour l'offre $idOffre a été lu.");
        return $result;
    }

    public function createAvis($titre, $date_experience, $id_membre, $id_offre, $note, $contexte_passage, $commentaire = null, $id_avis_reponse = null)
    {
        $resultatSQL = $this->model::createAvis($titre, $date_experience, $id_membre, $id_offre, $note, $contexte_passage, $commentaire, $id_avis_reponse);

        if ($resultatSQL) {
            $this->model::log("Un avis a été créé.");
            return $resultatSQL;
        } else {
            $this->model::log("Impossible de créer l'avis.");
            return -1;
        }
    }

    public function createReponsePro($id_avis, $commentaire, $id_pro)
    {
        $avisInitial = $this->model::getAvisById($id_avis);
        if (!$avisInitial) {
            $this->model::log("L'avis $id_avis n'existe pas.");
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
            $this->model::log("Une réponse a été créée.");
            return $resultatSQL;
        } else {
            $this->model::log("Impossible de créer la réponse.");
            return -1;
        }
    }

    public function updateAvis($id, $titre = false, $commentaire = false, $date_experience = false, $id_pro = false, $id_offre = false, $id_avis_reponse = false)
    {
        if ($titre === false && $commentaire === false && $date_experience === false && $id_pro === false && $id_offre === false && $id_avis_reponse === false) {
            $this->model::log("Aucune information n'a été modifiée.");
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
                $this->model::log("L'avis $id a été mis à jour.");
                return $updated_id_avis;
            } else {
                $this->model::log("Impossible de mettre à jour l'avis.");
                return -1;
            }
        }
    }

    public function deleteAvis($id)
    {
        if ($this->model::deleteAvis($id)) {
            $this->model::log("L'avis $id a été supprimé.");
            return 0;
        } else {
            $this->model::log("Impossible de supprimer l'avis $id.");
            return -1;
        }
    }
}
