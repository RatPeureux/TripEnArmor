<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/model/membre.php';

class MembreController
{

    private $model;

    function __construct()
    {
        $this->model = 'Membre';
    }

    public function createMembre($email, $mdp, $tel, $adresseId, $pseudo, $prenom, $nom)
    {
        $membreID = $this->model::createMembre($email, $mdp, $tel, $adresseId, $pseudo, $prenom, $nom);

        $this->model::log("Un membre a été créé.");
        return $membreID;
    }

    public function getInfosMembre($id)
    {
        $membre = $this->model::getMembreById($id);

        $this->model::log("Les informations du membre $id ont été lues.");
        return $membre;
    }

    public function getMdpMembre($id)
    {
        $membre = $this->model::getMdpById($id);

        if ($membre) {
            $result = $membre["mdp_hash"];
        } else {
            $this->model::log("Le mot de passe du membre $id n'a pas été trouvé.");
            return false;
        }

        $this->model::log("Le mot de passe du membre $id a été lu.");
        return $result;
    }

    public function updateMembre($id, $email = false, $mdp = false, $tel = false, $adresseId = false, $pseudo = false, $prenom = false, $nom = false)
    {
        if ($email === false && $mdp === false && $tel === false && $adresseId === false && $pseudo === false && $prenom === false && $nom === false) {
            $this->model::log("Aucune information n'a été modifiée.");
            return -1;
        } else {
            $membre = $this->model::getMembreById($id);

            $updatedMembreId = $this->model::updateMembre(
                $id,
                $email !== false ? $email : $membre["email"],
                $mdp !== false ? $mdp : $membre["mdp_hash"],
                $tel !== false ? $tel : $membre["num_tel"],
                $adresseId !== false ? $adresseId : $membre["id_adresse"],
                $pseudo !== false ? $pseudo : $membre["pseudo"],
                $prenom !== false ? $prenom : $membre["prenom"],
                $nom !== false ? $nom : $membre["nom"]
            );

            $this->model::log("Les informations du membre $id ont été modifiées.");
            return $updatedMembreId;
        }
    }

    public function deleteMembre($id)
    {
        $membre = $this->model::deleteMembre($id);

        $this->model::log("Le membre $id a été supprimé.");
        return $membre;
    }
}