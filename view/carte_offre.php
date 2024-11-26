<?php
if (!function_exists('chaineVersMot')) {
    function chaineVersMot($str): string
    {
        return str_replace('_', " d'", ucfirst($str));
    }
}
?>

<!--
    ### CARD COMPONENT ! ###
    Composant dynamique (généré avec les données en php)
    Impossible d'en faire un composant pur (statique), donc écrit en HTML pur (copier la forme dans le php)
-->
<a href='/scripts/go_to_details.php?id_offre=<?php echo $id_offre ?>'>

    <!-- CARTE VERSION TÉLÉPHONE -->
    <div class='card md:hidden <?php if ($option) {
        echo "active";
    } ?> relative bg-base100 rounded-xl flex flex-col'>
        <!-- En-tête -->
        <div
            class='en-tete absolute top-0 w-72 max-w-full bg-bgBlur/75 backdrop-blur left-1/2 -translate-x-1/2 rounded-b-lg'>
            <h3 class='text-xl text-center font-bold'>
                <?php echo $titre_offre; ?>
            </h3>
            <div class='flex w-full justify-between px-2'>
                <p class='text-small'><?php echo $pro['nom_pro'] ?></p>
                <p class='text-small'><?php echo chaineVersMot($categorie_offre) ?></p>
            </div>
        </div>
        <!-- Image de fond -->
        <img class="h-48 w-full rounded-t-lg object-cover" src='/public/images/<?php echo $categorie_offre ?>.jpg'
            alt="Image promotionnelle de l'offre">
        <!-- Infos principales -->
        <div class='infos flex items-center justify-around gap-2 px-2 grow'>
            <!-- Localisation -->
            <div class='localisation flex flex-col gap-2 flex-shrink-0 justify-center items-center'>
                <i class='fa-solid fa-location-dot'></i>
                <p class='text-small'><?php echo $ville ?></p>
                <p class='text-small'><?php echo $code_postal ?></p>
            </div>
            <hr class='h-20 border-black border'>
            <!-- Description avec les tags-->
            <div class='description py-2 flex flex-col gap-2 justify-center self-stretch'>
                <div class='p-1 rounded-lg bg-secondary self-center w-full'>
                    <p class='text-white text-center'>
                        <?php
                        // Afficher les tags / plats de l'offre, sinon mentionner l'absence de ces derniers
                        if ($tags) {
                            echo $tags;
                        } else {
                            echo 'Aucun tag';
                        }
                        ?>
                    </p>
                </div>
                <p class='overflow-hidden line-clamp-2 text-small'>
                    <?php echo $resume ?>
                </p>
            </div>
            <hr class='h-20 border-black border'>
            <!-- Notation et Prix -->
            <div class='localisation flex flex-col flex-shrink-0 gap-2 justify-center items-center'>
                <p class='text-small' title='<?php echo $title_prix ?>'><?php echo $prix_a_afficher ?></p>
            </div>
        </div>
    </div>

    <!-- CARTE VERSION TABLETTE -->
    <div class='card md:block hidden <?php if ($option) {
        echo "active";
    } ?> relative bg-base100 rounded-lg'>
        <div class="flex flex-row">
            <!-- Partie gauche -->
            <div class='gauche grow relative shrink-0 basis-1/2 h-[280px] overflow-hidden'>
                <!-- Image de fond -->
                <img class='rounded-l-lg w-full h-full object-cover object-center'
                    src='/public/images/<?php echo $categorie_offre ?>.jpg' alt="Image promotionnelle de l'offre">
            </div>
            <!-- Partie droite (infos principales) -->
            <div class='infos flex flex-col basis-1/2 p-3 justify-between relative'>
                <!-- En tête avec titre -->
                <div class='en-tete relative top-0 max-w-full rounded-lg'>
                    <h3 class='text-xl font-bold'>
                        <?php echo $titre_offre; ?>
                    </h3>
                    <div class='flex'>
                        <p class='text-small'><?php echo $pro['nom_pro']; ?></p>
                        <p class='text-small'><?php echo ', ' . chaineVersMot($categorie_offre); ?></p>
                    </div>
                </div>

                <!-- Description + tags -->
                <div class='description py-2 flex flex-col gap-2 self-stretch grow'>
                    <div class='p-1 rounded-lg bg-secondary self-center w-full'>
                        <p class='text-white text-center'>
                            <?php
                            // Afficher les tags / plats de l'offre, sinon mentionner l'absence de ces derniers
                            if ($tags) {
                                echo $tags;
                            } else {
                                echo 'Aucun tag';
                            }
                            ?>
                        </p>
                    </div>
                    <p class='overflow-hidden line-clamp-5 text-small'>
                        <?php echo $resume ?>
                    </p>
                </div>
                <!-- A droite, en bas -->
                <div class='self-stretch flex flex-col gap-2'>
                    <hr class='border-black w-full'>
                    <div class='flex justify-around self-stretch'>
                        <!-- Localisation -->
                        <div class='localisation flex gap-2 flex-shrink-0 justify-center items-center'>
                            <i class='fa-solid fa-location-dot'></i>
                            <p class='text-small'><?php echo $ville ?></p>
                            <p class='text-small'><?php echo $code_postal ?></p>
                        </div>
                        <!-- Notation et Prix -->
                        <div class='localisation flex flex-col flex-shrink-0 gap-2 justify-center items-center'>
                            <p class='text-small' title='<?php echo $title_prix ?>'><?php echo $prix_a_afficher ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</a>