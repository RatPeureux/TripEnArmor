<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/type_repas_restaurant.php";

class TypeRepasRestaurantController
{
    private $model;

    function __construct()
    {
        // Instanciation correcte du modèle TypeRepasRestaurant
        $this->model = new TypeRepasRestaurant();
    }

    public function getTypeRepasRestaurant($id_offre)
    {
        return $this->model::getTypeRepasRestaurantById($id_offre);
    }

    public function getTypeRepasRestaurantByName($name)
    {
        // Correction de l'appel à la méthode en tenant compte de la gestion des résultats
        $typeRepasRestaurant = $this->model::getTypesRepasRestaurantByName($name);
        return count($typeRepasRestaurant) > 0 ? $typeRepasRestaurant : false;
    }

    public function createTypeRepasRestaurant($nom_type_repas)
    {
        return $this->model::createTypeRepasRestaurant($nom_type_repas);
    }

    /**
     * Met à jour les types de repas associés à une offre.
     *
     * @param int $id_offre ID de l'offre
     * @param array $noms_type_repas Tableau des noms des types de repas à associer
     * @return bool
     */
    public function updateTypeRepasRestaurant($id_offre, $noms_type_repas)
    {
        // Vérification des paramètres
        if (empty($noms_type_repas)) {
            echo "ERREUR: Aucun type de repas à associer.";
            return false;
        }

        // Suppression des types de repas existants pour l'offre
        if (!$this->deleteTypeRepasByOffre($id_offre)) {
            return false;
        }

        // Ajout des nouveaux types de repas
        foreach ($noms_type_repas as $nom_type_repas) {
            $nom_type_repas = trim($nom_type_repas);

            // Vérifie si le type de repas existe, sinon le crée
            $existingType = $this->getTypeRepasRestaurantByName($nom_type_repas);
            if ($existingType) {
                $typeId = $existingType[0]['id_offre'];
            } else {
                // Si le type de repas n'existe pas, on le crée et récupère son ID
                $typeId = $this->createTypeRepasRestaurant($nom_type_repas);
            }

            // Lier le type de repas à l'offre
            if (!$this->linkTypeRepasToOffre($id_offre, $typeId)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Supprime tous les types de repas associés à une offre.
     *
     * @param int $id_offre ID de l'offre
     * @return bool
     */
    private function deleteTypeRepasByOffre($id_offre)
    {
        // Si l'appel à la méthode échoue, on retourne false
        return $this->model::deleteTypeRepasByOffre($id_offre);
    }

    /**
     * Lie un type de repas à une offre.
     *
     * @param int $id_offre ID de l'offre
     * @param int $id_type_repas ID du type de repas
     * @return bool
     */
    private function linkTypeRepasToOffre($id_offre, $id_type_repas)
    {
        // Vérification si la liaison a bien réussi
        return $this->model::linkTypeRepasToOffre($id_offre, $id_type_repas);
    }
}
