<!-- 
    carteActivite: Affiche les infos de l'activité dont l'id est passé en paramètre sous forme de carte
-->
<div>
    <!--
        Les vues sont des composants (briques Lego): des blocs de code que l'on importe dans une page/composant.
        Ce n'est pas 1 Modèle et 1 Composant pour 1 vue. Il peut y avoir 2, 3, 5, 12 vues pour un modèle et un composant.
        1 Modèle <-> 1 Controller <-> N Vues
        Ils ont souvent besoins de paramètres d'entrées
    -->
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