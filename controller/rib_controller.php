<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/rib.php";

class RibController
{
    private $model;

    function __construct()
    {
        $this->model = 'Rib';
    }

    public function getInfosRib($id)
    {
        $rib = $this->model::getRibById($id);

        $res = [
            "code_banque" => $rib["code_banque"],
            "code_guichet" => $rib["code_guichet"],
            "numero_compte" => $rib["numero_compte"],
            "cle" => $rib["cle"]
        ];

        $this->model::log("Les informations du rib $id ont été lues.");
        return $res;
    }

    public function createRib($code_banque, $code_guichet, $numero_compte, $cle)
    {
        $rib = $this->model::createRib($code_banque, $code_guichet, $numero_compte, $cle);

        $this->model::log("Un rib a été créé.");
        return $rib;
    }

    public function updateRib($id, $code_banque = false, $code_guichet = false, $numero_compte = false, $cle = false)
    {
        if ($code_guichet === false && $numero_compte === false && $cle === false) {
            $this->model::log("Aucune information n'a été modifiée.");
            return -1;
        } else {
            $rib = $this->model::getRibById($id);

            $res = $this->model::updateRib(
                $id,
                $code_banque !== false ? $code_banque : $rib["code_banque"],
                $code_guichet !== false ? $code_guichet : $rib["code_guichet"],
                $numero_compte !== false ? $numero_compte : $rib["numero_compte"],
                $cle !== false ? $cle : $rib["cle"]
            );

            $this->model::log("Les informations du rib $id ont été modifiées.");
            return $res;
        }
    }

    public function deleteRib($id)
    {
        $rib = $this->model::deleteRib($id);

        $this->model::log("Le rib $id a été supprimé.");
        return $rib;
    }
}