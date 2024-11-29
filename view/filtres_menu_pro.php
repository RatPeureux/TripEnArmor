

<!-- MENU FILTRE TÉLÉPHONE -->
<div class="top-0 block md:hidden flex flex-col justify-between absolute w-full h-full bg-base100 -translate-x-full duration-200 z-50" id="filtres">
    <div>
        <div class="p-4 gap-4 flex justify-start items-center h-20 border-b-2 border-black">
            <i class="text-3xl fa-solid fa-circle-xmark hover:cursor-pointer" onclick="toggleFiltres()"></i>
            <h1 class="text-h1">Filtres</h1>
        </div>

        <div class="w-full">
            <div class="flex flex-col w-full border-b-2 border-black p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f1-tel">
                    <p>Catégorie</p>
                    <p class="arrow" id="arrow-f1-tel">></p>
                </div>
                <div class="developped hidden text-small flex flex-wrap gap-4" id="developped-f1-tel">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="restauration-tel" />
                        <label for="restauration-tel">Restauration</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="activite-tel" />
                        <label for="activite-tel">Activité</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="spectacle-tel" />
                        <label for="spectacle-tel">Spectacle</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="visite-tel" />
                        <label for="visite-tel">Visite</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="parc_attraction-tel" />
                        <label for="parc_attraction-tel">Parc d'attraction</label>
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full border-b-2 border-black p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f2-tel">
                    <p>Disponibilité</p>
                    <p class="arrow" id="arrow-f2-tel">></p>
                </div>
                <div class="developped hidden text-small flex flex-wrap gap-4" id="developped-f2-tel">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" class="mb-1" id="open-tel" />
                        <label for="open-tel">Ouvert</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="close-tel" />
                        <label for="close-tel">Fermé</label>
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full border-b-2 border-black p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f3-tel">
                    <p>Localisation</p>
                    <p class="arrow" id="arrow-f3-tel">></p>
                </div>
                <div class="developped hidden flex flex-nowrap w-full items-center gap-4" id="developped-f3-tel">
                    <div class="text-nowrap text-small flex items-center gap-2 w-full">
                        <label>Ville ou Code postal</label>
                        <input id="localisation-tel" type="text" class="w-full border border-base300 rounded-lg p-1 focus:ring-0" />
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full border-b-2 border-black p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f4-tel">
                    <p>Note générale</p>
                    <p class="arrow" id="arrow-f4-tel">></p>
                </div>
                <div class="developped hidden flex items-center" id="developped-f4-tel">
                    <label class="text-small">Intervalle des prix entre&nbsp;</label>
                    <div class="flex items-center">
                        <input id="min-note-tel" type="number" value="0" min="0" max="5" step="0.5" class="border border-base300 rounded-lg p-1 text-small text-right w-[39px] focus:ring-0" />
                        &nbsp;
                        <img src="/public/icones/egg-full.svg" class="mb-1" width="11">
                    </div>
                    <label class="text-small">&nbsp;et&nbsp;</label>
                    <div class="flex items-center">
                        <input id="max-note-tel" type="number" value="5" min="0" max="5" step="0.5" class="border border-base300 rounded-lg p-1 text-small text-right w-[39px] focus:ring-0" />
                        &nbsp;
                        <img src="/public/icones/egg-full.svg" class="mb-1" width="11">
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full border-b-2 border-black p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f5-tel">
                    <p>Période</p>
                    <p class="arrow" id="arrow-f5-tel">></p>
                </div>
                <div class="developped text-small hidden flex flex-wrap items-center" id="developped-f5-tel">
                    <div>
                        <label>Offre allant du&nbsp;</label>
                        <input type="date" class="border border-base300 rounded-lg p-1 text-right mr-4" id="min-date-tel" name="min-date-tel">
                    </div>
                    <div>
                        <label>&nbsp;au&nbsp;</label>
                        <input type="date" class="border border-base300 rounded-lg p-1 text-right" id="max-date-tel" name="max-date-tel">
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full border-b-2 border-black p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f6-tel">
                    <p>Prix</p>
                    <p class="arrow" id="arrow-f6-tel">></p>
                </div>
                <div class="developped hidden flex items-center" id="developped-f6-tel">
                    <label class="text-small">Intervalle des prix entre&nbsp;</label>
                    <input id="min-price-tel" type="number" value="0" min="0" max="99" class="border border-base300 rounded-lg p-1 text-small text-right w-[34px] focus:ring-0" />
                    <label class="text-small">&nbsp;€&nbsp;et&nbsp;</label>
                    <input id="max-price-tel" type="number" value="99" min="0" max="99" class="border border-base300 rounded-lg p-1 text-small text-right w-[34px] focus:ring-0" />
                    <label class="text-small">&nbsp;€</label>
                </div>
            </div>        
        <?php if ($pro['data']['type'] === 'prive') { ?>
            <div class="flex flex-col w-full border-b-2 border-black p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f7-tel">
                    <p>Type d'offre</p>
                    <p class="arrow" id="arrow-f7-tel">></p>
                </div>
                <div class="developped hidden flex items-center" id="developped-f7-tel">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="standard-tel" />
                        <label for="standard-tel">Standard</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="premium-tel" />
                        <label for="premium-tel">Premium</label>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>

    <a href="#" class="uppercase bg-primary font-bold text-h4 text-white text-center m-2 p-4" onclick="toggleFiltres()">
        Voir les offres
    </a>
</div>