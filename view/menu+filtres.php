<!--
    Composant menu du visiteur / membre
    (responsive)
-->

<!-- VERSION PHONE -->
<div class="md:hidden h-full bg-base100 fixed top-0 w-7/12 left-0 -translate-x-full duration-200 z-50">
    <div class="p-4 flex flex-row gap-3 justify-start items-center h-20 <?php if (!isset($pagination)) {
        echo 'bg-primary text-white';
    } ?>">
        <i class="text-3xl fa-solid fa-circle-xmark hover:cursor-pointer" onclick="toggleMenu()"></i>
        <h1 class="text-h1">Menu</h1>
    </div>
    <div class="all-items flex flex-col items-stretch">
        <a class="pl-5 py-3 border-t-2 border-black <?php if (isset($pagination) && $pagination == 1) {
            echo 'active';
        } ?>" href="/">Accueil</a>
        <a class="pl-5 py-3 border-t-2 border-black flex justify-between pr-2 <?php if (isset($pagination) && $pagination == 2) {
            echo 'active';
        } ?>" href="/">
            <p>Toutes les offres</p>
            <p>></p>
        </a>
        <a class="pl-10 py-3 border-t-2 border-black <?php if (isset($pagination) && $pagination == 3) {
            echo 'active';
        } ?>" href="/offre/a-la-une">À la une</a>
        <a class="pl-10 py-3 border-t-2 border-black <?php if (isset($pagination) && $pagination == 4) {
            echo 'active';
        } ?>" href="/offre/consultees-recemment">Consultées récemment</a>
        <a class="pl-10 py-3 border-t-2 border-black <?php if (isset($pagination) && $pagination == 5) {
            echo 'active';
        } ?>" href="/offre/nouveau">Nouveautés</a>
    </div>
</div>

<div id="layer-background" onclick="toggleMenu()" class="hidden fixed w-full h-full top-0 left-0 z-40"></div>

<!-- VERSION TABLETTE OU PLUS (+768px) -->
<div class="sticky top-2 hidden md:block flex flex-col min-w-52 h-[80.5vh] scroll-hidden overflow-x-hidden overflow-y-auto">
    <div class="w-52 border-black border rounded-b-lg rounded-tr-lg z-25">
        <div class="p-4 flex flex-row gap-3 justify-start items-center rounded-tr-lg <?php if (!isset($pagination)) {
            echo 'bg-primary text-white';
        } ?>">
            <i class="text-3xl fa-solid fa-bars"></i>
            <h1 class="text-h1">Menu</h1>
        </div>
        <div class="all-items flex flex-col items-stretch">
            <a class="pl-5 py-3 border-t-2 border-black <?php if (isset($pagination) && $pagination == 1) {
            echo 'active';
            } ?>" href="/">Accueil</a>
            <a class="pl-5 py-3 border-t-2 border-black flex justify-between pr-2 <?php if (isset($pagination) && $pagination == 2) {
                echo 'active';
            } ?>" href="/">
                <p>Toutes les offres</p>
                <p>></p>
            </a>
            <a class="pl-10 py-3 border-t-2 border-black <?php if (isset($pagination) && $pagination == 3) {
                echo 'active';
            } ?>" href="/offre/a-la-une">À la une
            </a>
            <a class="pl-10 py-3 border-t-2 border-black <?php if (isset($pagination) && $pagination == 4) {
                echo 'active';
            } ?>" href="/offre/consultees-recemment">Consultées récemment
            </a>
            <a class="pl-10 py-3 border-t-2 border-black <?php if (isset($pagination) && $pagination == 5) {
                echo 'active';
            } ?>" href="/offre/nouveau">Nouveautés
            </a>
        </div>
    </div>

    <a class="mx-2 mt-4 self-end flex items-center gap-2 hover:text-primary duration-100" onclick="allDevelopped()">
        <i class="text xl fa-solid fa-filter"></i>
        <p>Tout découvrir</p>
    </a>
    
    <div class="w-52 border-black border rounded-b-lg rounded-tr-lg z-25">
        <div class="flex flex-col w-full p-3 gap-4">
            <div class="flex justify-between cursor-pointer" id="button-f1-tab">
                <p>Catégorie</p>
                <p class="arrow" id="arrow-f1-tab">></p>
            </div>
            <div class="alldevelopped hidden text-small flex flex-wrap gap-4" id="developped-f1-tab">
                <div class="flex items-center gap-2">
                    <input type="checkbox" class="mb-1" id="restauration-tab" />
                    <label for="restauration-tab">Restauration</label>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" class="mb-1" id="activite-tab" />
                    <label for="activite-tab">Activité</label>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" class="mb-1" id="spectacle-tab" />
                    <label for="spectacle-tab">Spectacle</label>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" class="mb-1" id="visite-tab" />
                    <label for="visite-tab">Visite</label>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" class="mb-1" id="parc_attraction-tab" />
                    <label for="parc_attraction-tab">Parc d'attraction</label>
                </div>
            </div>
        </div>
        <div class="flex flex-col w-full border-t-2 border-black p-3 gap-4">
            <div class="flex justify-between cursor-pointer" id="button-f2-tab">
                <p>Disponibilité</p>
                <p class="arrow" id="arrow-f2-tab">></p>
            </div>
            <div class="alldevelopped hidden text-small flex flex-wrap gap-4" id="developped-f2-tab">
                <div class="flex items-center gap-2">
                    <input type="checkbox" class="mb-1" class="mb-1" id="ouvert-tab" />
                    <label for="ouvert-tab">Ouvert</label>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" class="mb-1" id="ferme-tab" />
                    <label for="ferme-tab">Fermé</label>
                </div>
            </div>
        </div>
        <div class="flex flex-col w-full border-t-2 border-black p-3 gap-4">
            <div class="flex justify-between cursor-pointer" id="button-f3-tab">
                <p>Localisation</p>
                <p class="arrow" id="arrow-f3-tab">></p>
            </div>
            <div class="alldevelopped hidden flex flex-col w-full" id="developped-f3-tab">
                <label class="text-small">Ville ou Code postal</label>
                <input id="localisation-tab" type="text" class="w-full border border-base300 rounded-lg p-1 focus:ring-0" />
            </div>
        </div>
        <div class="flex flex-col w-full border-t-2 border-black p-3 gap-4">
            <div class="flex justify-between cursor-pointer" id="button-f4-tab">
                <p>Note générale</p>
                <p class="arrow" id="arrow-f4-tab">></p>
            </div>
            <div class="alldevelopped hidden flex-col" id="developped-f4-tab">
                <label class="text-small">Intervalle des prix entre :&nbsp;</label>
                <div class="flex items-center">
                    <div class="flex items-center">
                        <input id="min-note-tab" type="number" value="0" min="0" max="5" step="0.5" class="border border-base300 rounded-lg p-1 text-small text-right w-[39px] focus:ring-0" />
                        &nbsp;
                        <img src="/public/icones/egg-full.svg" class="mb-1" width="11">
                    </div>
                    <label class="text-small">&nbsp;et&nbsp;</label>
                    <div class="flex items-center">
                        <input id="max-note-tab" type="number" value="5" min="0" max="5" step="0.5" class="border border-base300 rounded-lg p-1 text-small text-right w-[39px] focus:ring-0" />
                        &nbsp;
                        <img src="/public/icones/egg-full.svg" class="mb-1" width="11">
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden flex flex-col w-full border-t-2 border-black p-3 gap-4">
            <div class="flex justify-between cursor-pointer" id="button-f5-tab">
                <p>Période</p>
                <p class="arrow" id="arrow-f5-tab">></p>
            </div>
            <div class="alldevelopped text-small hidden flex flex-wrap items-center" id="developped-f5-tab">
                <div>
                    <label>Offre allant du&nbsp;</label>
                    <input type="date" class="border border-base300 rounded-lg p-1 text-right mr-4" id="min-date-tab" name="min-date-tab">
                    &nbsp;
                </div>
                <div>
                    <label>au&nbsp;</label>
                    <input type="date" class="border border-base300 rounded-lg p-1 text-right" id="max-date-tab" name="max-date-tab">
                </div>
            </div>
        </div>
        <div class="flex flex-col w-full border-t-2 border-black p-3 gap-4">
            <div class="flex justify-between cursor-pointer" id="button-f6-tab">
                <p>Prix</p>
                <p class="arrow" id="arrow-f6-tab">></p>
            </div>
            <div class="alldevelopped hidden flex flex-wrap items-center justify-between gap-2" id="developped-f6-tab">
                <div class="flex flex-col">
                    <label class="text-small">Intervalle des prix entre :&nbsp;</label>
                    <div class="flex items-center">
                        <input id="min-price-tab" type="number" value="0" min="0" max="99" class="w-[44px] border border-base300 rounded-lg p-1 text-small text-right focus:ring-0" />
                        <label class="text-small">&nbsp;€&nbsp;et&nbsp;</label>
                        <input id="max-price-tab" type="number" value="<?php echo $prix_mini_max;?>" min="0" max="<?php echo $prix_mini_max;?>" class="w-[44px] border border-base300 rounded-lg p-1 text-small text-right focus:ring-0" />
                        <label class="text-small">&nbsp;€</label>
                    </div>
                </div>
                <div class="text-small flex flex-wrap" id="developped-f6-tab">
                    <label class="text-small">Restauration :&nbsp;</label>
                    <div class="w-full flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="mb-1" id="€-tab" />
                            <label for="€-tab">€</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="mb-1" id="€€-tab" />
                            <label for="€€-tab">€€</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="mb-1" id="€€€-tab" />
                            <label for="€€€-tab">€€€</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function allDevelopped() {
        const developped = document.querySelectorAll('.alldevelopped');
        const arrows = document.querySelectorAll('.arrow');

        developped?.forEach((section) => {
            section.classList.remove('hidden');
        });

        arrows?.forEach((icon) => {
            icon.classList.add('rotate-90');
        });
    }
</script>