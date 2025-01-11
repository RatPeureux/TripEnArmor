<!-- 
    Composant du footer pour les visiteurs / membres
    Pour l'ajouter, écrier la balise <div id='footer'></div> dans votre code html
    (responsive)
-->
<footer class="bg-secondary flex text-white justify-center items-center p-8 pb-16 md:pb-8 gap-5">
    <img src="/public/icones/logo-footer.svg" alt="[img] Logo">
    <!-- Format ordinateur -->
    <div class="text-center hidden md:block">
        <div>
            <a href="/mentions" class="hover:text-primary">Mentions légales</a>
            -
            <a href="/cgu" class="hover:text-primary">CGU</a>
            -
            <a href="mailto:benoit.tottereau@univ-rennes.fr" class="hover:text-primary">Contacter le support</a>
            -
            <a href="/pro/connexion" class="hover:text-primary">Vous êtes un professionnel ?</a>
        </div>
        @2025, <a href="/TripEnArvor" class="hover:text-primary">TripEnArvor</a>
    </div>
    <!-- Format téléphone et tablette -->
    <div class="text-center block md:hidden">
        <div>
            <a href="/mentions" class="hover:text-primary">Mentions légales</a>
            -
            <a href="/cgu" class="hover:text-primary">CGU</a>
            -
            <a href="mailto:pact.tripenarvor@gmail.com" class="hover:text-primary">Contacter le support</a>
            -
            <a href="/pro/connexion" class="hover:text-primary">Vous êtes un professionnel ?</a>
            -
            @2025, <a href="/TripEnArvor" class="hover:text-primary">TripEnArvor</a>
        </div>
    </div>
</footer>