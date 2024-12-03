<?php

class ImageController
{
    private $uploadDir;
    public function __construct()
    {
        $this->uploadDir = dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/images/';
    }

    public function getImagesOfOffre($id_offre)
    {
        $result = [
            "carte" => false,
            "plan" => false,
            "details" => false
        ];
        $allImages = scandir($this->uploadDir);

        foreach ($allImages as $image) {
            $name = explode(".", $image)[0];
            $subparts = explode("-", $name);

            if ($subparts[0] == $id_offre) {
                if ($subparts[1] == "carte") {
                    $result["carte"] = $image;
                } else if ($subparts[1] == "plan") {
                    $result["plan"] = $image;
                } else {
                    if ($result["details"] === false) {
                        $result["details"] = [];
                    }
                    $result["details"][] = $image;
                }
            }
        }

        return $result;
    }

    public function uploadImage($id_offre, $champ, $actual_path, $extension)
    {
        echo "In uploadImage<br>";
        echo "id_offre : " . $id_offre . "<br>";
        echo "champ : " . $champ . "<br>";
        echo "actual_path : " . $actual_path . "<br>";
        echo "extension : " . $extension . "<br>";
        echo "uploadDir : " . $this->uploadDir . "<br>";
        echo "new path : " . $this->uploadDir . $id_offre . "_" . $champ . '.' . $extension . "<br>";
        echo "is uploaded file : " . is_uploaded_file($actual_path) . "<br>";

        var_dump(is_uploaded_file($actual_path));
        echo '<br>';
        echo substr(sprintf('%o', fileperms($actual_path)), -4);
        echo '<br>';

        if (is_uploaded_file($actual_path)) {
            if (!is_dir($this->uploadDir)) {
                mkdir($this->uploadDir, 0777, true);
            }
            $result = rename($actual_path, $this->uploadDir . $id_offre . "_" . $champ . '.' . $extension);
        } else {
            $result = "TEEEST";
        }

        // $result = move_uploaded_file($actual_path, $this->uploadDir . $id_offre . "_" . $champ . '.' . $extension);

        var_dump($result);

        echo "<br>";

        echo "move_uploaded_file result : " . ($result ? "true" : "false") . "<br>";
        return $result;
    }
}