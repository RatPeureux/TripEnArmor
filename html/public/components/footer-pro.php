<!-- 
    Composant du footer pour le pro
    Pour l'ajouter, écrier la balise <div id='footer'></div> dans votre code html
-->
<footer class="bg-secondary flex flex-col items-center text-white justify-center p-8 mt-2">
        <!-- Format ordinateur et tablette -->
        <div class="text-center hidden sm:block">
            <div>
                <a href="/pro/legal" class="hover:text-primary">Mentions légales</a>
                -
                <a href="/pro/cgu" class="hover:text-primary">CGU</a>
                -
                <a href="/pro/support" class="hover:text-primary">Contacter le support</a>
                -
                <a href="/" class="hover:text-primary">Retour vers la PACT</a>
            </div>
            @2024, <a href="#" class="hover:text-primary">TripEnArmor</a>
        </div>
        <!-- Format téléphone -->
        <div class="text-center block sm:hidden">
            <div>
                <a href="/pro/legal" class="hover:text-primary">Mentions légales</a>
                -
                <a href="/pro/cgu" class="hover:text-primary">CGU</a>
                -
                <a href="/pro/support" class="hover:text-primary">Contacter le support</a>
                -
                <a href="/" class="hover:text-primary">Retour vers la PACT</a>
                -
                <a href="#">@2024, TripEnArmor</a>
            </div>
        </div>
</footer>
