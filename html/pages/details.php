<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/output.css">
    <script type="module" src="/scripts/main.js" defer></script>
    <script src="/scripts/loadcaroussel.js" type="module"></script>

    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <title>Détails d'une offre</title>
</head>
<body class="flex flex-col">

    <div id="header" class="mb-10"></div>

    <!-- VERSION TELEPHONE -->
    <main class="phone md:hidden flex flex-col"> 

        <div id="menu"></div>

        <!-- Slider des images de présentation -->
        <div class="w-full h-80 overflow-hidden relative swiper default-carousel swiper-container">
            <!-- Wrapper -->
            <div class="swiper-wrapper">
                <!-- Image n°1 -->
                <div class="swiper-slide">
                    <img class="object-cover w-full h-full" src="/public/images/image-test.jpg" alt="">
                </div>
                <!-- Image n°2... etc -->
                <div class="swiper-slide">
                    <img class="object-cover w-full h-full" src="/public/images/image-test2.jpg" alt="">
                </div>
                <div class="swiper-slide">
                    <img class="object-cover w-full h-full" src="/public/images/pp.png" alt="">
                </div>
            </div>
            <!-- Boutons de navigation sur la slider -->
            <a onclick="history.back()" class="border absolute top-2 left-2 z-20 p-2 bg-bgBlur/75 rounded-lg flex justify-center items-center"><i class="fa-solid fa-arrow-left"></i></a>
            <div class="swiper-pagination"></div>
        </div>

        <!-- Reste des informations sur l'offre -->
        <div class="px-3 flex flex-col gap-5">
            <!-- Titre de l'offre -->
            <h1 class="text-h1">Restaurant le Hingar de Brélévenez</h1>

            <!-- Nom du professionnel -->
            <p class="text-small">Restaurant le Brélévenez</p>

            <!-- Prix + localisation -->
            <div class="localisation-et-prix flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <i class="fa-solid fa-location-dot"></i>
                    <div class="text-small">
                        <p>Lannion, 22300</p>
                        <p>1 rue du Stang ar Beo</p>
                    </div>
                </div>
                <p class="prix font-bold">10-80€</p>
            </div>

            <!-- Description détaillée -->
            <div class="description flex flex-col gap-2">
                <h3>À propos</h3>
                <p class="text-justify text-small px-2">
                    Priscilla en salle, son mari Christophe chef de cuisine et toute l'équipe vous accueillent dans leur restaurant,
                    ouvert depuis Janvier 2018, dans le quartier Historique De Lannion : "Brélévenez"
                    <br>
                    Quartier célèbre pour son église avec son escalier de 142 marches pour y accéder.
                    Christophe vous propose une cuisine de produits locaux et de saisons.
                    Restaurant ouvert à l'année.
                    Fermé mardi et mercredi toute la journée et le samedi midi.
                    (Parking privé)
                </p>
            </div>
        </div>
    </main>
    
    <!-- VERSION TABLETTE -->
    <main class="hidden md:block mx-10 self-center rounded-lg p-2 max-w-[1280px]">
        <div class="flex gap-3">
            <!-- PARTIE GAUCHE (menu) -->
            <div id="menu"></div>
            
            <!-- PARTIE DROITE (offre & détails) -->
            <div class="tablette grow p-4 flex flex-col items-center gap-4">

                <!-- CAROUSSEL -->
                <div class="w-full h-[500px] overflow-hidden relative swiper default-carousel swiper-container">
                    <!-- Wrapper -->
                    <div class="swiper-wrapper">
                        <div class="swiper-slide !w-full">
                            <img class="object-cover w-full h-full" src="/public/images/image-test.jpg" alt="">
                        </div>
                        <div class="swiper-slide !w-full">
                            <img class="object-cover w-full h-full" src="/public/images/image-test2.jpg" alt="">
                        </div>
                        <div class="swiper-slide !w-full">
                            <img class="object-cover w-full h-full" src="/public/images/pp.png" alt="">
                        </div>
                    </div>
                    <!-- Boutons de navigation sur la slider -->
                    <div class="flex items-center gap-8 justify-center">
                        <a class="swiper-button-prev group flex justify-center items-center border border-solid rounded-full !top-1/2 -translate-y-1/2 !left-5 !bg-primary !text-white after:!text-base">
                        </a>
                        <a class="swiper-button-next group flex justify-center items-center border border-solid rounded-full !top-1/2 -translate-y-1/2 !right-5 !bg-primary !text-white after:!text-base">
                        </a>
                    </div>
                    <a onclick="history.back()" class="border absolute top-2 left-2 z-20 p-2 bg-bgBlur/75 rounded-lg flex justify-center items-center"><i class="fa-solid fa-arrow-left text-h1"></i></a>
                    <div class="swiper-pagination"></div>
                </div>

                <!-- RESTE DES INFORMATIONS SUR L'OFFRE -->
                <div class="flex flex-col gap-2">
                    <h1 class="text-h1">Restaurant le Hingar le Brélévenez</h1>
                
                    <!-- Description + avis -->
                    <div class="description-et-avis">

                        <!-- Partie description -->
                        <div class="partie-description flex flex-col gap-4">
                            <p class="professionnel">Restaurant le Brélévenez</p>

                            <!-- Notation -->

                            <!-- Prix + localisation -->
                            <div class="localisation-et-prix flex flex-col gap-4">
                                <div class="flex items-center gap-4">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <div class="text-small">
                                        <p>Lannion, 22300</p>
                                        <p>1 rue du Stang ar Beo</p>
                                    </div>
                                </div>
                                <p class="prix font-bold">10-80€</p>
                            </div>

                            <!-- Description détaillée -->
                            <div class="description flex flex-col gap-2">
                                <h3>À propos</h3>
                                <p class="text-justify text-small px-2">
                                    Priscilla en salle, son mari Christophe chef de cuisine et toute l'équipe vous accueillent dans leur restaurant,
                                    ouvert depuis Janvier 2018, dans le quartier Historique De Lannion : "Brélévenez"
                                    <br>
                                    Quartier célèbre pour son église avec son escalier de 142 marches pour y accéder.
                                    Christophe vous propose une cuisine de produits locaux et de saisons.
                                    Restaurant ouvert à l'année.
                                    Fermé mardi et mercredi toute la journée et le samedi midi.
                                    (Parking privé)
                                </p>
                            </div>
                        </div>

                        <!-- Partie avis -->
                        <div class="avis"></div>
                    </div>
                
                </div>
            
            </div>
        </div>
    </main>
    
    <div id="footer"></div>

</body>
</html>
