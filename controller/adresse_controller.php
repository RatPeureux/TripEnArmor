<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/adresse.php";


class AdresseController
{
   private $model;


   function __construct()
   {
       $this->model = 'Adresse';
   }


   public function getInfosAdresse($id)
   {
       $adresse = $this->model::getAdresseById($id);


       $res = [
           "code_postal" => $adresse["code_postal"],
           "ville" => $adresse["ville"],
           "numero" => $adresse["numero"],
           "odonyme" => $adresse["odonyme"],
           "complement" => $adresse["complement"]
       ];


        $this->model::log("Les informations de l'adresse $id ont été lues.");
        return $res;
    }

   public function createAdresse($code_postal, $ville, $numero, $odonyme, $complement)
   {
       $adresse = $this->model::createAdresse($code_postal, $ville, $numero, $odonyme, $complement);


        $this->model::log("Une adresse a été créée.");
        return $adresse;
    }

    public function updateAdresse($id, $code_postal = false, $ville = false, $numero = null, $odonyme = null, $complement = null)
    {
        if ($ville === false && $numero === false && $odonyme === false && $complement === false) {
            $this->model::log("Aucune information n'a été modifiée.");
            return -1;
        } else {
            $adresse = $this->model::getAdresseById($id);
        }


       $res = $this->model::updateAdresse(
           $id,
           $code_postal !== false ? $code_postal : $adresse["code_postal"],
           $ville !== false ? $ville : $adresse["ville"],
           $numero !== false ? $numero : $adresse["numero"],
           $odonyme !== false ? $odonyme : $adresse["odonyme"],
           $complement !== false ? $complement : $adresse["complement"]
       );


       if (!$res) {
           throw new Exception("Échec de la mise à jour.");
       }


       echo "Mise à jour réussie.";
       return $res;
}




   public function deleteAdresse($id)
   {
       $adresse = $this->model::deleteAdresse($id);


        $this->model::log("L'adresse $id a été supprimée.");
        return $adresse;
    }
}

