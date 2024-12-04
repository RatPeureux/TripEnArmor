<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/model/pro_prive.php';

class ProPriveController
{

    private $model;

    function __construct()
    {
        $this->model = 'ProPrive';
    }

    public function createProPrive($email, $mdp, $tel, $adresseId, $nom_pro, $num_siren, $id_rib)
    {
        $proPriveID = $this->model::createProPublic($email, $mdp, $tel, $adresseId, $nom_pro, $num_siren, $id_rib);
        return $proPriveID;
    }

    public function getInfosProPrive($id)
    {
        $proPrive = $this->model::getProPriveById($id);

        if ($proPrive) {
            $result = [
                "id_compte" => $proPrive["id_compte"],
                "email" => $proPrive["email"],
                "tel" => $proPrive["num_tel"],
                "id_adresse" => $proPrive["id_adresse"],
                "nom_pro" => $proPrive["nom_pro"],
                "num_siren" => $proPrive["num_siren"],
                "id_rib" => $proPrive["id_rib"]
            ];
        } else {
            return false;
        }

        return $result;
    }

    public function getMdpProPrive($id)
    {
        $proPrive = $this->model::getMdpById($id);

        if ($proPrive) {
            $result = $proPrive["mdp_hash"];
        } else {
            return false;
        }

        return $result;
    }

    public function updateProPrive($id, $email = false, $mdp = false, $tel = false, $adresseId = false, $nom_pro = false, $num_siren = false, $id_rib = false)
    {
        if ($email === false && $mdp === false && $tel === false && $adresseId === false && $nom_pro === false && $num_siren === false && $id_rib === false) {
            echo "ERREUR: Aucun champ à modifier";
            return -1;
        } else {
            $proPrive = $this->model::getProPriveById($id);

            $updatedProPriveId = $this->model::updateProPrive(
                $id,
                $email !== false ? $email : $proPrive["email"],
                $mdp !== false ? $mdp : $proPrive["mdp_hash"],
                $tel !== false ? $tel : $proPrive["num_tel"],
                $adresseId !== false ? $adresseId : $proPrive["id_adresse"],
                $nom_pro !== false ? $nom_pro : $proPrive["nom_pro"],
                $num_siren !== false ? $num_siren : $proPrive["num_siren"],
                $id_rib !== false ? $id_rib : $proPrive["id_rib"]
            );
            return $updatedProPriveId;
        }
    }

    public function deleteProPrive($id)
    {
        $proPrive = $this->model::deleteProPrive($id);

        return $proPrive;
    }
}