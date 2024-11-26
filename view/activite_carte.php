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
            $allCardsTextPhone .= "<a href='/scripts/go_to_details.php?id_offre=$id_offre'>
                        <div class='card";
                // Afficher en exergue si la carte a une option (à la une ou en relief)
                if ($option) {
                    $allCardsTextPhone .= ' active';
                }
                $allCardsTextPhone .= " relative bg-base200 rounded-xl flex flex-col'>
                                <!-- En tête -->
                                <div
                                    class='en-tete absolute top-0 w-72 max-w-full bg-bgBlur/75 backdrop-blur left-1/2 -translate-x-1/2 rounded-b-lg'>
                                    <h3 class='text-center font-bold'>$titre_offre</h3>
                                <div class='flex w-full justify-between px-2'>
                                    <p class='text-small'>$pro_nom</p>
                                    <p class='text-small'>$categorie_offre</p>
                                </div>
                            </div>
                            <!-- Image de fond -->
                            <img class='h-48 w-full rounded-t-lg object-cover' src='/public/images/image-test.png'
                                alt='Image promotionnelle de l'offre'>
                            <!-- Infos principales -->
                            <div class='infos flex items-center justify-around gap-2 px-2 grow'>
                                <!-- Localisation -->
                                <div class='localisation flex flex-col gap-2 flex-shrink-0 justify-center items-center'>
                                    <i class='fa-solid fa-location-dot'></i>
                                    <p class='text-small'>$ville</p>
                                    <p class='text-small'>$code_postal</p>
                                </div>
                                <hr class='h-20 border-black border'>
                                <!-- Description -->
                                <div class='description py-2 flex flex-col gap-2 justify-center self-stretch'>
                                    <div class='p-1 rounded-lg bg-secondary self-center'>
                                        <p class='text-white text-center text-small font-bold'>";
                // Afficher les tags / plats de l'offre, sinon mentionner l'absence de ces derniers
                if ($tags) {
                    $allCardsTextPhone .= $tags;
                } else {
                    $allCardsTextPhone .= 'Aucun tag';
                }
                $allCardsTextPhone .= "</p>
                                    </div>
                                    <p class='overflow-hidden line-clamp-2 text-small'>
                                        $resume
                                    </p>
                                </div>
                                <hr class='h-20 border-black border'>
                                <!-- Notation et Prix -->
                                <div class='localisation flex flex-col flex-shrink-0 gap-2 justify-center items-center'>
                                    <p class='text-small'>$prix_a_afficher</p>
                                </div>
                            </div>
                        </div>
                    </a>"
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