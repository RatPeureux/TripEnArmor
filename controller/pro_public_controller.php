<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/model/pro_public.php';

class ProPublicController
{
    private $model;

    function __construct()
    {
        $this->model = 'ProPublic';
    }

    public function createProPublic($email, $mdp, $tel, $adresseId, $nom_pro, $type_orga)
    {
        $proPublicID = $this->model::createProPublic($email, $mdp, $tel, $adresseId, $nom_pro, $type_orga);

        $this->model::log("Un professionnel public a été créé.");
        return $proPublicID;
    }
    public function getInfosProPublic($id)
    {
        $result = $this->model::getProPublicById($id);

        $this->model::log("Les informations du professionnel public $id ont été lues.");
        return $result;
    }

    public function getMdpProPublic($id)
    {
        $proPrive = $this->model::getMdpById($id);

        if ($proPrive) {
            $result = $proPrive["mdp_hash"];
        } else {
            $this->model::log("Le mot de passe du professionnel public $id n'a pas été trouvé.");
            return false;
        }

        $this->model::log("Le mot de passe du professionnel public $id a été lu.");
        return $result;
    }



    public function updateProPublic($id, $email = false, $mdp = false, $tel = false, $adresseId = false, $nom_pro = false, $type_orga = false)
    {
        if ($email === false && $mdp === false && $tel === false && $adresseId === false && $nom_pro === false && $type_orga === false) {
            $this->model::log("Aucune information n'a été modifiée.");
            return -1;
        } else {
            $proPublic = $this->model::getProPublicById($id);

            $updatedProPublicId = $this->model::updateProPublic(
                $id,
                $email !== false ? $email : $proPublic["email"],
                $mdp !== false ? $mdp : $proPublic["mdp_hash"],
                $tel !== false ? $tel : $proPublic["num_tel"],
                $adresseId !== false ? $adresseId : $proPublic["id_adresse"],
                $nom_pro !== false ? $nom_pro : $proPublic["nom_pro"],
                $type_orga !== false ? $type_orga : $proPublic["type_orga"]
            );
            $this->model::log("Les informations du professionnel public $id ont été modifiées.");
            return $updatedProPublicId;
        }
    }

    public function deleteProPublic($id)
    {
        $proPublic = $this->model::deleteProPublic($id);

        $this->model::log("Le professionnel public $id a été supprimé.");
        return $proPublic;
    }
}