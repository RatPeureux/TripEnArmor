<?php

class ImageController {
    private $uploadDir;
    public function __construct() {
        $this->uploadDir = dirname($_SERVER['DOCUMENT_ROOT']) . '/public/images/';
    }

    public function getImagesOfOffre($id_offre) {
        $result = [
            "carte" => "",
            "plan" => "",
            "details" => []
        ];
        $allImages = scandir($this->uploadDir);

        foreach( $allImages as $image ) {
            $name = explode(".", $image)[0];
            $subparts = explode("_", $name);

            if ( $subparts[0] == $id_offre ) {
                $result[$subparts[1]] = $image;
                // if ( $subparts[1] == "carte" ) {
                //     $result["carte"] = $image;
                // } else if ( $subparts[1] == "plan" ) {
                //     $result["plan"] = $image;
                // } else {
                //     $result["details"][] = $image;
                // }
            }
        }

        return $result;
    }

    public function uploadImage($id_offre, $champ, $actual_path, $extension) {
        return move_uploaded_file($actual_path, $this->uploadDir . $id_offre . "_" . $champ . '.' . $extension);
    }
}