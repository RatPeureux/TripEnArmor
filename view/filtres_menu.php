

<!-- MENU FILTRE TÉLÉPHONE -->
<div class="block md:hidden flex flex-col justify-between absolute w-full h-full bg-base100 -translate-x-full duration-200 z-50" id="filtres">
    <div>
        <div class="p-4 gap-4 flex justify-start items-center h-20 border-b-2 border-black">
            <i class="text-3xl fa-solid fa-circle-xmark hover:cursor-pointer" onclick="toggleFiltres()"></i>
            <h1 class="text-h1">Filtres</h1>
        </div>

        <div class="w-full">
            <div class="flex flex-col w-full border-b-2 border-black p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f1-tel">
                    <p>Catégorie</p>
                    <p id="arrow-f1-tel">></p>
                </div>
                <div class="developped hidden text-small flex flex-wrap gap-4" id="developped-f1-tel">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="restauration-tel" name="restauration-tel" />
                        <label>Restauration</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="activite-tel" name="activite-tel" />
                        <label>Activité</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="spectacle-tel" name="spectacle-tel" />
                        <label>Spectacle</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="visite-tel" name="visite-tel" />
                        <label>Visite</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="parc_attraction-tel" name="parc_attraction-tel" />
                        <label>Parc d'attraction</label>
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full border-b-2 border-black p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f2-tel">
                    <p>Disponibilité</p>
                    <p id="arrow-f2-tel">></p>
                </div>
                <div class="developped hidden text-small flex flex-wrap gap-4" id="developped-f2-tel">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" class="mb-1" id="open-tel" name="open-tel" />
                        <label>Ouvert</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="close-tel" name="close-tel" />
                        <label>Fermé</label>
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full border-b-2 border-black p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f3-tel">
                    <p>Localisation</p>
                    <p id="arrow-f3-tel">></p>
                </div>
                <div class="developped hidden flex flex-nowrap w-full items-center gap-4" id="developped-f3-tel">
                    <div class="text-nowrap text-small flex items-center gap-2 w-full">
                        <label>Ville</label>
                        <label class="text-[#999999]">ou</label>
                        <label>Code postal</label>
                        <input id="localisation-tel" type="text" class="w-full bg-base100 border border-[#999999] rounded-lg p-1 focus:ring-0" />
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full border-b-2 border-black p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f4-tel">
                    <p>Note générale</p>
                    <p id="arrow-f4-tel">></p>
                </div>
                <div class="developped hidden flex items-center" id="developped-f4-tel">
                    <label class="text-small">Intervale des prix entre&nbsp;</label>
                    <div class="flex items-center">
                        <input id="min-note-tel" type="number" value="0" min="0" max="5" step="0.5" class="bg-base100 text-small text-right w-[39px] focus:ring-0" />
                        &nbsp;
                        <img src="/public/icones/egg-full.svg" class="mb-1" width="11">
                    </div>
                    <label class="text-small">&nbsp;et&nbsp;</label>
                    <div class="flex items-center">
                        <input id="max-note-tel" type="number" value="5" min="0" max="5" step="0.5" class="bg-base100 text-small text-right w-[39px] focus:ring-0" />
                        &nbsp;
                        <img src="/public/icones/egg-full.svg" class="mb-1" width="11">
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full border-b-2 border-black p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f5-tel">
                    <p>Période</p>
                    <p id="arrow-f5-tel">></p>
                </div>
                <div class="developped text-small hidden flex items-center" id="developped-f5-tel">
                    <label>Offre allant du&nbsp;</label>
                    <input type="date" class="bg-base100 text-right mr-4" id="min-date-tel" name="min-date-tel">
                    <label>&nbsp;au&nbsp;</label>
                    <input type="date" class="bg-base100 text-right" id="max-date-tel" name="max-date-tel">
                </div>
            </div>
            <div class="flex flex-col w-full border-b-2 border-black p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f6-tel">
                    <p>Prix</p>
                    <p id="arrow-f6-tel">></p>
                </div>
                <div class="developped hidden flex items-center" id="developped-f6-tel">
                    <label class="text-small">Intervale des prix entre&nbsp;</label>
                    <input id="min-price-tel" type="number" value="0" min="0" max="99" class="bg-base100 text-small text-right w-[34px] focus:ring-0" />
                    <label class="text-small">&nbsp;€&nbsp;et&nbsp;</label>
                    <input id="max-price-tel" type="number" value="99" min="0" max="99" class="bg-base100 text-small text-right w-[34px] focus:ring-0" />
                    <label class="text-small">&nbsp;€</label>
                </div>
            </div>
        </div>
    </div>

    <a href="#" class="uppercase bg-primary font-bold text-white text-center m-2 p-4" onclick="toggleFiltres()">
        Voir les offres
    </a>
</div>