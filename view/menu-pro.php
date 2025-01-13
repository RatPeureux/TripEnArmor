<!--
    Composant menu du pro
-->
<div class="h-full bg-white fixed top-0 w-1/4 left-0 -translate-x-full duration-200 z-50">
    <div class="p-4 flex flex-row gap-3 justify-start items-center h-20 <?php if (!isset($pagination)) {
        echo 'bg-primary text-white';
    } ?>">
        <i class="text-3xl fa-solid fa-circle-xmark cursor-pointer" onclick="toggleMenu()"></i>
        <h1 class="text-h1">Menu</h1>
    </div>
    <div class="all-items flex flex-col items-stretch">
        <a class="pl-5 py-3 border-t border-black <?php if (isset($pagination) && $pagination == 1) {
            echo 'active';
        } ?>" href="/pro">Mes offres</a>
        <a class="pl-5 py-3 border-t border-black <?php if (isset($pagination) && $pagination == 2) {
            echo 'active';
        } ?>" href="/pro/offre/creer">Créer une offre</a>
    </div>
</div>

<div id="layer-background" onclick="toggleMenu()" class="hidden fixed w-full h-full top-0 left-0 z-40"></div>