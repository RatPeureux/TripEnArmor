<!-- 
    Composant du footer pour les visiteurs / membres
    Pour l'ajouter, écrier la balise <div id='footer'></div> dans votre code html
    (responsive)
-->
<footer class="bg-secondary flex text-white justify-center items-center p-8 gap-5 mt-2">
    <img src="/public/images/logo-footer.svg" alt="[img] Logo">
    <!-- Format ordinateur -->
    <div class="text-center hidden md:block">
            <div>
                <a href="/pro/legal" class="hover:text-primary">Mentions légales</a>
                -
                <a href="/pro/cgu" class="hover:text-primary">CGU</a>
                -
                <a href="/pro/support" class="hover:text-primary">Contacter le support</a>
                -
                <a href="/pro" class="hover:text-primary">Vous êtes un professionnel ?</a>
            </div>
            @2024, <a href="#" class="hover:text-primary">TripEnArmor</a>
        </div>
        <!-- Format téléphone et tablette -->
        <div class="text-center block md:hidden">
            <div>
                <a href="/pro/legal" class="hover:text-primary">Mentions légales</a>
                -
                <a href="/pro/cgu" class="hover:text-primary">CGU</a>
                -
                <a href="/pro/support" class="hover:text-primary">Contacter le support</a>
                -
                <a href="/pro" class="hover:text-primary">Vous êtes un professionnel ?</a>
                -
                @2024, <a href="#" class="hover:text-primary">TripEnArmor</a>
            </div>
        </div>
</footer>
