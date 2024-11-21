<div>
    <?php
    echo $id_activite;
    try {
        if (isset($id_activite)) {
            $controler = new ActiviteController();

            $data = $controler->getInfosActivite($id_activite);
            ?>
            <div>
                <pre>
                        <?php var_dump($data); ?>
                    </pre>
            </div>

            <?php
        } else {
            ?>
            <div>
                <h1>ERREUR : Aucun id d'activité spécifié</h1>
            </div>
            <?php
        }
    } catch (Exception $e) {
        echo "ERREUR : " . $e->getMessage() . "\n";
    }
    ?>
</div>