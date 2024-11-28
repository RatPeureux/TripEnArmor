<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/t_image_img.php";

class TImageImgController {
    private $model;  

    function __construct() {
        $this->model = 'TImageImg';
    }

    public function getPathToPlan($id_parc) {
        $t_image_img = $this->model::getPathToPlan($id_parc);

        return $t_image_img["path"];
    }

    public function getInfosImage($id) {
        $t_image_img = $this->model::getImageByPath($id);

        $res = [
            "img_path" => $t_image_img["path"]
        ];

        return $res;
    }

    public function createImage($path) {
        $t_image_img = $this->model::createImage($path);

        return $t_image_img;
    }
    
    public function updateImage($path, $new_path = false) {
        if ($new_path === false) {
            echo "ERREUR : Aucun champ à modifier";
            return -1;
        } else {
            $t_image_img = $this->model::getImageByPath($path);
            
            $res = $this->model::updateImage(
                $path,
                $new_path !== false ? $new_path : $t_image_img["new_path"]
            );

            return $res;
        }
    }

    public function deleteImage($path) {
        $t_image_img = $this->model::deleteImage($path);

        return $t_image_img;
    }
}