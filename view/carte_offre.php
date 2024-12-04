<!-- FONCTION UTILES -->
<?php
if (!function_exists('chaineVersMot')) {
    function chaineVersMot($str): string
    {
        return str_replace('_', " d'", ucfirst($str));
    }
}

// Obtenir les différentes variables avec les infos nécessaires via des requêtes SQL
require dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/get_details_offre.php';

if ($mode_carte == 'membre') {
    ?>
    <!--
    !!! CARD COMPONENT MEMBER !!!
    Composant dynamique (généré avec les données en php)
    Impossible d'en faire un composant pur (statique), donc écrit en HTML pur (copier la forme dans le php)
-->
    <a class="card" href='/scripts/go_to_details.php?id_offre=<?php echo $id_offre ?>'>

        <!-- CARTE VERSION TÉLÉPHONE -->
        <div class='md:hidden <?php if ($option) {
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
                    <p class='categorie text-small'><?php echo chaineVersMot($categorie_offre) ?></p>
                </div>
            </div>
            <!-- Image de fond -->
            <?php
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/image_controller.php';
            $controllerImage = new ImageController();
            $images = $controllerImage->getImagesOfOffre($id_offre);
            ?>
            <img class="h-48 w-full rounded-t-lg object-cover" src='/public/images/<?php if ($images['carte']) {
                echo $images['carte'];
            } else {
                echo $categorie_offre . '.jpg';
            } ?>' alt="Image promotionnelle de l'offre">
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
                <div class='flex flex-col gap-2 justify-center items-center'>
                    <?php
                    // Moyenne des notes quand il y en a une
                    if ($moyenne) {
                        $n = $moyenne;
                        ?>
                        <div class="note flex gap-1 flex-wrap" title="<?php echo $moyenne;?>">
                            <?php for ($i = 0; $i < 5; $i++) {
                                if ($n > 1) {
                                    ?>
                                    <img class="w-2" src="/public/images/oeuf_plein.svg" alt="1 point de note">
                                    <?php
                                } else if ($n > 0) {
                                    ?>
                                        <img class="w-2" src="/public/images/oeuf_moitie.svg" alt="0.5 point de note">
                                    <?php
                                } else {
                                    ?>
                                        <img class="w-2" src="/public/images/oeuf_vide.svg" alt="0 point de note">
                                    <?php
                                }
                                $n--;
                            }
                            ?>
                            <p class='text-small italic flex items-center'>(<?php echo $nb_avis ?>)</p>
                        </div>
                        <?php
                    }
                    ?>
                    <p class='text-small' title='<?php echo "Fourchette des prix : Min " . $tarif_min . ", Max " . $tarif_max ?>'><?php echo $prix_a_afficher ?></p>
                </div>
            </div>
        </div>

        <!-- CARTE VERSION TABLETTE -->
        <div class='md:block hidden <?php if ($option) {
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
                        <div class="flex w-full">
                            <h3 class='text-xl font-bold grow'>
                                <?php echo $titre_offre ?>
                            </h3>
                            <?php
                            // Moyenne des notes quand il y en a une
                            if ($moyenne) {
                                $n = $moyenne;
                                ?>
                                <div class="flex gap-1">
                                    <div class="note flex gap-1 shrink-0" title="<?php echo $moyenne;?>">
                                        <?php for ($i = 0; $i < 5; $i++) {
                                            if ($n > 1) {
                                                ?>
                                                <img class="w-3" src="/public/images/oeuf_plein.svg" alt="1 point de note">
                                                <?php
                                            } else if ($n > 0) {
                                                ?>
                                                    <img class="w-3" src="/public/images/oeuf_moitie.svg" alt="0.5 point de note">
                                                <?php
                                            } else {
                                                ?>
                                                    <img class="w-3" src="/public/images/oeuf_vide.svg" alt="0 point de note">
                                                <?php
                                            }
                                            $n--;
                                        }
                                        ?>
                                    </div>
                                    <p class='text-small italic flex items-center'>(<?php echo $nb_avis ?>)</p>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <div class='flex'>
                            <p class='text-small'><?php echo $pro['nom_pro'] ?></p>
                            <p class='categorie text-small'><?php echo ', ' . chaineVersMot($categorie_offre); ?></p>
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
                            <div class='flex flex-col flex-shrink-0 gap-2 justify-center items-center'>
                                <p class='text-small' title='<?php echo "Fourchette des prix : Min " . $tarif_min . ", Max " . $tarif_max ?>'><?php echo $prix_a_afficher ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </a>
    <?php
} else {
    ?>
    <!--
    !!! CARD COMPONENT PRO !!!
    Composant dynamique (généré avec les données en php)
    Impossible d'en faire un composant pur (statique), donc écrit en HTML pur (copier la forme dans le php)
    -->
    <div class="card <?php if ($option)
        echo 'active' ?> relative max-w-[1280px] bg-base100 rounded-lg flex">

            <!-- PARTIE DE GAUCHE, image-->
            <div class="gauche relative shrink-0 basis-1/2 h-[370px] overflow-hidden">
                <a href="/scripts/go_to_details_pro.php?id_offre=<?php echo $id_offre ?>">
                <?php
                require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/image_controller.php';
                $controllerImage = new ImageController();
                $images = $controllerImage->getImagesOfOffre($id_offre);

                ?>
                <img class="rounded-l-lg w-full h-full object-cover object-center" src='/public/images/<?php if ($images['carte']) {
                    echo "offres/" . $images['carte'];
                } else {
                    echo $categorie_offre . '.jpg';
                } ?>' alt="Image promotionnelle de l'offre" title="consulter les détails">
            </a>
        </div>

        <!-- PARTIE DE DROITE (infos principales) -->
        <div class="infos relative flex flex-col items-center basis-1/2 self-stretch px-5 py-3 justify-between">

            <div class="w-full">
                <!-- A droite, en haut -->
                <div class="flex w-full items-center justify-between">

                    <!-- Titre de l'offre -->
                    <div>
                        <h3 class="text-h2 font-bold"><?php echo $titre_offre ?></h3>
                        <div class="flex">
                            <p class="text"><?php echo $pro['nom_pro'] ?></p>
                            <p class="categorie text"><?php echo ', ' . chaineVersMot($categorie_offre) ?></p>
                        </div>
                    </div>

                    <?php
                    // Moyenne des notes quand il y en a une
                    if ($moyenne) {
                        $n = $moyenne;
                        ?>
                        <div class="flex gap-1 self-end">
                            <div class="note flex gap-1 shrink-0 m-1" title="<?php echo $moyenne;?>">
                                <?php for ($i = 0; $i < 5; $i++) {
                                    if ($n > 1) {
                                        ?>
                                        <img class="w-3" src="/public/images/oeuf_plein.svg" alt="1 point de note">
                                        <?php
                                    } else if ($n > 0) {
                                        ?>
                                            <img class="w-3" src="/public/images/oeuf_moitie.svg" alt="0.5 point de note">
                                        <?php
                                    } else {
                                        ?>
                                            <img class="w-3" src="/public/images/oeuf_vide.svg" alt="0 point de note">
                                        <?php
                                    }
                                    $n--;
                                }
                                ?>
                            </div>
                            <p class='text-small italic flex items-center'>(<?php echo $nb_avis ?>)</p>
                        </div>
                        <?php
                    }
                    ?>

                    <!-- Manipulations sur l'offre -->
                    <div class="flex gap-10 self-start items-center justify-center">
                        <!-- en ligne ? -->
                        <?php
                        if ($est_en_ligne) {
                            ?>
                            <a href="/scripts/toggleLigne.php?id_offre=<?php echo $id_offre ?>"
                                onclick="return confirm('Voulez-vous vraiment mettre <?php echo $titre_offre ?> hors ligne ?');"
                                title=" [!!!] mettre hors-ligne">
                                <svg class="toggle-wifi-offline p-1 rounded-lg border-rouge-logo hover:border-y-2 border-solid duration-100 hover:fill-[#EA4335]"
                                    width="55" height="40" viewBox="0 0 40 32" fill="#0a0035">
                                    <path
                                        d="M3.3876 12.6812C7.7001 8.54375 13.5501 6 20.0001 6C26.4501 6 32.3001 8.54375 36.6126 12.6812C37.4126 13.4437 38.6751 13.4187 39.4376 12.625C40.2001 11.8313 40.1751 10.5625 39.3814 9.8C34.3563 4.96875 27.5251 2 20.0001 2C12.4751 2 5.64385 4.96875 0.612605 9.79375C-0.181145 10.5625 -0.206145 11.825 0.556355 12.625C1.31885 13.425 2.5876 13.45 3.38135 12.6812H3.3876ZM20.0001 16C23.5501 16 26.7876 17.3188 29.2626 19.5C30.0939 20.2313 31.3564 20.15 32.0876 19.325C32.8189 18.5 32.7376 17.2312 31.9126 16.5C28.7376 13.7 24.5626 12 20.0001 12C15.4376 12 11.2626 13.7 8.09385 16.5C7.2626 17.2312 7.1876 18.4938 7.91885 19.325C8.6501 20.1562 9.9126 20.2313 10.7439 19.5C13.2126 17.3188 16.4501 16 20.0064 16H20.0001ZM24.0001 26C24.0001 24.9391 23.5787 23.9217 22.8285 23.1716C22.0784 22.4214 21.061 22 20.0001 22C18.9392 22 17.9218 22.4214 17.1717 23.1716C16.4215 23.9217 16.0001 24.9391 16.0001 26C16.0001 27.0609 16.4215 28.0783 17.1717 28.8284C17.9218 29.5786 18.9392 30 20.0001 30C21.061 30 22.0784 29.5786 22.8285 28.8284C23.5787 28.0783 24.0001 27.0609 24.0001 26Z" />
                                    <path class="invisible" d="M31 26.751L6 2.75098" stroke-width="3" stroke="#EA4335"
                                        stroke-linecap="round" />
                                </svg>
                            </a>
                            <?php
                        } else {
                            ?>
                            <a href="/scripts/toggleLigne.php?id_offre=<?php echo $id_offre ?>"
                                onclick="return confirm('Voulez-vous vraiment mettre <?php echo $titre_offre ?> en ligne ?');"
                                title="[!!!] mettre en ligne">
                                <svg class="toggle-wifi-online p-1 rounded-lg hover:fill-[#00350D] border-secondary hover:border-y-2 border-solid duration-100"
                                    width="55" height="40" viewBox="0 0 40 32" fill="#EA4335">
                                    <path
                                        d="M3.3876 12.6812C7.7001 8.54375 13.5501 6 20.0001 6C26.4501 6 32.3001 8.54375 36.6126 12.6812C37.4126 13.4437 38.6751 13.4187 39.4376 12.625C40.2001 11.8313 40.1751 10.5625 39.3814 9.8C34.3563 4.96875 27.5251 2 20.0001 2C12.4751 2 5.64385 4.96875 0.612605 9.79375C-0.181145 10.5625 -0.206145 11.825 0.556355 12.625C1.31885 13.425 2.5876 13.45 3.38135 12.6812H3.3876ZM20.0001 16C23.5501 16 26.7876 17.3188 29.2626 19.5C30.0939 20.2313 31.3564 20.15 32.0876 19.325C32.8189 18.5 32.7376 17.2312 31.9126 16.5C28.7376 13.7 24.5626 12 20.0001 12C15.4376 12 11.2626 13.7 8.09385 16.5C7.2626 17.2312 7.1876 18.4938 7.91885 19.325C8.6501 20.1562 9.9126 20.2313 10.7439 19.5C13.2126 17.3188 16.4501 16 20.0064 16H20.0001ZM24.0001 26C24.0001 24.9391 23.5787 23.9217 22.8285 23.1716C22.0784 22.4214 21.061 22 20.0001 22C18.9392 22 17.9218 22.4214 17.1717 23.1716C16.4215 23.9217 16.0001 24.9391 16.0001 26C16.0001 27.0609 16.4215 28.0783 17.1717 28.8284C17.9218 29.5786 18.9392 30 20.0001 30C21.061 30 22.0784 29.5786 22.8285 28.8284C23.5787 28.0783 24.0001 27.0609 24.0001 26Z" />
                                    <path class="visible" d="M31 26.751L6 2.75098" stroke-width="3" stroke="#EA4335"
                                        stroke-linecap="round" />
                                </svg>
                            </a>
                            <?php
                        }
                        ?>
                        <!-- modifier l'offre -->
                        <a href="" title="modifier l'offre">
                            <i class="fa-solid fa-gear text-secondary text-h1 hover:text-primary duration-100"></i>
                        </a>
                        <!-- détails de l'offre -->
                        <a href="/scripts/go_to_details_pro.php?id_offre=<?php echo $id_offre ?>" title="voir l'offre">
                            <i class="fa-solid fa-arrow-up-right-from-square text-h1 hover:text-primary duration-100"></i>
                        </a>
                    </div>
                </div>

                <!-- A droite, au milieu : description avec éventuels tags -->
                <div class=" description py-2 flex flex-col gap-2 w-full">
                    <div class="flex justify-center relative">
                        <div class="p-2 rounded-lg bg-secondary self-center w-full">
                            <p class="text-white text-center">
                                <?php
                                // Afficher les tags de l'offre (ou plats si c'est un resto), sinon indiquer qu'il n'y a aucun tag
                                if ($tags) {
                                    echo $tags;
                                } else {
                                    echo 'Aucun tag';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                    <p class="line-clamp-3">
                        <?php echo $resume ?>
                    </p>
                </div>
            </div>

            <!-- A droite, en bas -->
            <div class="self-stretch flex flex-col shrink-0 gap-2">
                <hr class="border-black w-full">
                <div class="flex justify-around self-stretch">
                    <!-- Localisation -->
                    <div title="localisation de l'offre"
                        class="localisation flex gap-2 flex-shrink-0 justify-center items-center">
                        <i class="fa-solid fa-location-dot"></i>
                        <p class="text-small"><?php echo $ville ?></p>
                        <p class="text-small"><?php echo $code_postal ?></p>
                    </div>

                    <?php
                    // Moyenne des notes quand il y en a une
                    if ($moyenne) {
                        $n = $moyenne;
                        ?>
                        <div class="flex gap-1 flex-wrap">
                            <?php for ($i = 0; $i < 5; $i++) {
                                if ($n > 1) {
                                    ?>
                                    <img class="w-3" src="/public/images/oeuf_plein.svg" alt="1 point de note">
                                    <?php
                                } else if ($n > 0) {
                                    ?>
                                        <img class="w-3" src="/public/images/oeuf_moitie.svg" alt="0.5 point de note">
                                    <?php
                                } else {
                                    ?>
                                        <img class="w-3" src="/public/images/oeuf_vide.svg" alt="0 point de note">
                                    <?php
                                }
                                $n--;
                            }
                            ?>
                            <p class='text-small italic flex items-center'>(<?php echo $nb_avis ?>)</p>
                        </div>
                        <?php
                    }
                    ?>

                    <!-- Notation et Prix -->
                    <div class="flex flex-col flex-shrink-0 gap-2 justify-center items-center">
                        <p class="text-small" title="<?php echo "Fourchette des prix : Min " . $tarif_min . ", Max " . $tarif_max ?>">
                            <?php echo $prix_a_afficher ?>
                        </p>
                    </div>
                </div>

                <!-- Infos supplémentaires pour le pro -->
                <div class="bg-base200 p-3 rounded-lg flex justify-around gap-1">

                    <!-- Avis & date de mise à jour -->
                    <div class="flex flex-col justif-around">
                        <div class="flex italic justify-start gap-4">
                            <!-- Non vus -->
                            <a title="avis non consultés" href="" class="hover:text-primary">
                                <i class=" fa-solid fa-exclamation text-rouge-logo"></i>
                                (0)
                            </a>
                            <!-- Non répondus -->
                            <a title="avis sans réponse" href="" class="hover:text-primary">
                                <i class="fa-solid fa-reply-all text-rouge-logo"></i>
                                (0)
                            </a>
                            <!-- Blacklistés -->
                            <a title="avis blacklistés" href="" class="hover:text-primary">
                                <i class="fa-regular fa-eye-slash text-rouge-logo"></i>
                                (0)
                            </a>
                        </div>
                        <p class="type-offre text-center grow" title="type de l'offre"><?php echo $type_offre ?></p>
                    </div>

                    <!-- Dates de mise à jour -->
                    <div class="flex justify-between text-small">
                        <div class="flex items-center justify-arround">
                            <i class="fa-solid fa-rotate text-xl"></i>
                            <p class="italic">Modifiée le <?php echo $date_mise_a_jour ?></p>
                        </div>
                    </div>

                    <!-- Type offre + options -->
                    <div class="flex flex-col justify-between gap-2">
                        <p class="type-offre text-center grow" title="type de l'offre"><?php echo $type_offre ?></p>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-gears text-xl"></i>
                            <div>
                                <p>‘A la Une’ 10/09/24-17/09/24</p>
                                <p>‘En relief' 10/09/24-17/09/24</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

<?php } ?>