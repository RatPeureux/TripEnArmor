<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../php_files/authentification.php';
if (isConnectedAsPro()) {
    ?>

    <footer class="bg-secondary text-white p-8 pb-16 md:pb-8 text-sm">
        <div class="mx-auto flex flex-col justify-center items-center">
            <div class="hidden md:flex w-full items-center justify-between">
                <a href="/pro/" class="self-start flex items-center gap-2">
                    <img src="/public/icones/logo-footer.svg" alt="[img] Logo">
                    <h1 class="font-cormorant text-white uppercase text-PACT">PACT</h1>
                </a>

                <a href="/" class="hover:text-primary">Retourner au tableau de bord</a>
            </div>

            <div class="md:hidden w-full flex flex-col items-center justify-center">
                <a href="/pro/" class="mx-auto self-start flex items-center gap-2">
                    <img src="/public/icones/logo-footer.svg" alt="[img] Logo">
                    <h1 class="font-cormorant text-white uppercase text-PACT">PACT</h1>
                </a>

                <a href="/" class="hover:text-primary">Retourner au tableau de bord</a>
            </div>

            <div class="w-full flex flex-col gap-8 mt-4">
                <div class="w-full flex items-center justify-between flex-col md:flex-row gap-4">
                    <div class="hidden md:flex">
                        <a href="/pro/mentions" class="hover:text-primary">Mentions légales</a>
                        ,&nbsp;
                        <a href="/pro/cgu" class="hover:text-primary">Conditions générales d'utilisation</a>
                        ,&nbsp;
                        <a href="/pro/cgv" class="hover:text-primary">Conditions générales de ventes</a>
                        ,&nbsp;
                        <a href="/pro/cgu" class="hover:text-primary">Politique en matière de cookies</a>
                    </div>

                    <div class="flex flex-col md:hidden items-center justify-center">
                        <a href="/pro/mentions" class="hover:text-primary">Mentions légales</a>
                        <a href="/pro/cgu" class="hover:text-primary">Conditions générales d'utilisation</a>
                        <a href="/pro/cgv" class="hover:text-primary">Conditions générales de ventes</a>
                        <a href="/pro/cgu" class="hover:text-primary">Politique en matière de cookies</a>
                    </div>

                    <a href="mailto:pact.tripenarvor@gmail.com" class="hover:text-primary">Contacter le support</a>
                </div>

                <div class="w-full flex items-center justify-between flex-col md:flex-row gap-4">
                    <div class="hidden md:block">
                        ©<?php echo date("Y"); ?> <a href="/pro/TripEnArvor" class="hover:text-primary">TripEnArvor</a>,
                        Association Loi 1901 Tous droits réservés
                    </div>

                    <div class="flex flex-col md:hidden items-center justify-center">
                        <div>
                            ©<?php echo date("Y"); ?> <a href="/pro/TripEnArvor" class="hover:text-primary">TripEnArvor</a>
                        </div>
                        Association Loi 1901 Tous droits réservés
                    </div>

                    <div>
                        Offres de Bretagne
                    </div>
                </div>
            </div>
        </div>
    </footer>

<?php } else { ?>

    <footer class="bg-secondary text-white p-8 pb-16 md:pb-8 text-sm">
        <div class="mx-auto max-w-[1280px] flex flex-col justify-center items-center">
            <div class="hidden md:flex w-full items-end justify-between">
                <a href="/" class="self-start flex items-center gap-2">
                    <img src="/public/icones/logo-footer.svg" alt="[img] Logo">
                    <h1 class="font-cormorant text-white uppercase text-PACT">PACT</h1>
                </a>

                <a href="/pro/connexion" class="hover:text-primary">Vous êtes un professionnel ?</a>
            </div>

            <div class="md:hidden w-full flex flex-col items-end justify-between">
                <a href="/" class="mx-auto self-start flex items-center gap-2">
                    <img src="/public/icones/logo-footer.svg" alt="[img] Logo">
                    <h1 class="font-cormorant text-white uppercase text-PACT">PACT</h1>
                </a>

                <a href="/pro/connexion" class="hover:text-primary">Vous êtes un professionnel ?</a>
            </div>

            <div class="w-full flex flex-col gap-8 mt-4">
                <div class="w-full flex items-center justify-between flex-col md:flex-row gap-4">
                    <div class="hidden md:flex">
                        <a href="/mentions" class="hover:text-primary">Mentions légales</a>
                        ,&nbsp;
                        <a href="/cgu" class="hover:text-primary">Conditions générales d'utilisation</a>
                        ,&nbsp;
                        <a href="/cgu" class="hover:text-primary">Politique en matière de cookies</a>
                    </div>

                    <div class="flex flex-col md:hidden items-center justify-center">
                        <a href="/mentions" class="hover:text-primary">Mentions légales</a>
                        <a href="/cgu" class="hover:text-primary">Conditions générales d'utilisation</a>
                        <a href="/cgu" class="hover:text-primary">Politique en matière de cookies</a>
                    </div>

                    <a href="mailto:pact.tripenarvor@gmail.com" class="hover:text-primary">Contacter le support</a>
                </div>

                <div class="w-full flex items-center justify-between flex-col md:flex-row gap-4">
                    <div class="hidden md:block">
                        ©<?php echo date("Y"); ?> <a href="/TripEnArvor" class="hover:text-primary">TripEnArvor</a>,
                        Association Loi 1901 Tous droits réservés
                    </div>

                    <div class="flex flex-col md:hidden items-center justify-center">
                        <div>
                            ©<?php echo date("Y"); ?> <a href="/TripEnArvor" class="hover:text-primary">TripEnArvor</a>
                        </div>
                        Association Loi 1901 Tous droits réservés
                    </div>

                    <div>
                        Offres de Bretagne
                    </div>
                </div>
            </div>
        </div>
    </footer>

<?php } ?>