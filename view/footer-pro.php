<!-- 
    Composant du footer pour le pro
    Pour l'ajouter, écrier la balise <div id='footer'></div> dans votre code html
-->
<footer class="bg-secondary flex flex-col items-center text-white justify-center p-8 pb-16 md:pb-8 mt-2">
    <!-- Format ordinateur et tablette -->
    <div class="text-center hidden sm:block">
        <div>
            <a href="/pro/mentions" class="hover:text-primary">Mentions légales</a>
            -
            <a href="/pro/cgu" class="hover:text-primary">CGU</a>
            -
            <a href="/pro/cgv" class="hover:text-primary">CGV</a>
            -
            <a href="mailto:pact.tripenarvor@gmail.com" class="hover:text-primary">Contacter le support</a>
            -
            <a href="/" class="hover:text-primary">Retour vers la PACT</a>
        </div>
        ©<?php echo date("Y"); ?>, <a href="/pro/TripEnArvor" class="hover:text-primary">TripEnArvor</a>
    </div>
    <!-- Format téléphone -->
    <div class="text-center block sm:hidden">
        <div>
            <a href="/pro/mentions" class="hover:text-primary">Mentions légales</a>
            -
            <a href="/pro/cgu" class="hover:text-primary">CGU</a>
            -
            <a href="/pro/cgv" class="hover:text-primary">CGV</a>
            -
            <a href="mailto:pact.tripenarvor@gmail.com" class="hover:text-primary">Contacter le support</a>
            -
            <a href="/" class="hover:text-primary">Retour vers la PACT</a>
            -
            ©<?php echo date("Y"); ?>, <a href="/pro/TripEnArvor">TripEnArvor</a>
        </div>
    </div>
</footer>