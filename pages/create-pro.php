<?php if (!isset($_POST['mail'])) { ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image" href="../public/images/favicon.png">
        <link rel="stylesheet" href="../styles/output.css">
        <title>Création de compte 1/2</title>
        <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>
    </head>
    <body class="h-screen bg-base100 p-4 overflow-hidden">
        <i onclick="history.back()" class="fa-solid fa-arrow-left fa-2xl cursor-pointer"></i>
        <div class="h-full flex flex-col items-center justify-center">
            <div class="relative w-full max-w-96 h-fit flex flex-col items-center justify-center sm:w-96 m-auto">
                <img class="absolute -top-24" src="../public/images/logo.svg" alt="moine" width="108">
                <form class="bg-base200 w-full p-5 rounded-lg border-2 border-secondary" action="create-pro.php" method="post" enctype="multipart/form-data">
                    <p class="pb-3">Je créer un compte Professionnel</p>

                    <div class="flex flex-nowrap space-x-3 mb-1.5">
                        <div class="w-full">
                            <label class="text-small" for="prenom">Prénom*</label>
                            <input class="p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="prenom" name="prenom" maxlength="40" pattern="^[A-Za-z]+[-]?[A-Za-z]+$" title="" required>
                        </div>
                        <div class="w-full">
                            <label class="text-small" for="nom">Nom*</label>
                            <input class="p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="nom" name="nom" maxlength="40" pattern="^[A-Za-z' -]+$" title="" required>
                        </div>
                    </div>
                    
                    <label class="text-small" for="mail">Adresse mail*</label>
                    <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="email" id="mail" name="mail" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="" required>
                    
                    <label class="text-small" for="passwd">Mot de passe*</label>
                    <input class="p-2 bg-base100 w-full h-12 mb-3 rounded-lg" type="password" id="passwd" name="passwd" autocomplete="new-password" pattern="[a-zA-Z ]*" title="" required>

                    <label class="text-small" for="passwd-conf">Confirmer le mot de passe*</label>
                    <input class="p-2 bg-base100 w-full h-12 mb-3 rounded-lg" type="password" id="passwd-conf" name="passwd-conf" autocomplete="current-password" pattern="[a-zA-Z ]*" title="" required>

                    <input type="submit" value="Continuer" class="cursor-pointer w-full h-12 mb-1.5 bg-secondary text-white font-bold rounded-lg inline-flex items-center justify-center border border-transparent focus:scale-[0.97] hover:bg-orange-600 hover:border-orange-600 hover:text-white">
                    <a href="login-pro.html" class="w-full h-12 p-1 bg-transparent text-secondary font-bold rounded-lg inline-flex items-center justify-center border border-secondary hover:text-white hover:bg-secondary hover:border-secondary focus:scale-[0.97]"> 
                        J'ai déjà un compte
                    </a>
                </form>
            </div>
        </div>
    </body>
    </html>
<?php } else { ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image" href="../public/images/favicon.png">
        <link rel="stylesheet" href="../styles/output.css">
        <title>Création de compte 2/2</title>
        <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>
    </head>
    <body class="h-screen bg-base100 pt-4 px-4 overflow-x-hidden">
        <i onclick="history.back()" class="absolute top-7 fa-solid fa-arrow-left fa-2xl cursor-pointer"></i>
        <div class="w-full max-w-96 h-fit flex flex-col items-end sm:w-96 m-auto">
            <img class="text mb-4" src="../public/images/logo.svg" alt="moine" width="57">
            <form class="bg-base200 w-full p-5 rounded-lg border-2 border-secondary" action="" method="post" enctype="multipart/form-data">
                <p class="pb-3">Dites-nous en plus !</p>

                <div class="flex flex-nowrap space-x-3 mb-1.5">
                    <div class="w-full">
                        <label class="text-small" for="prenom">Prénom*</label>
                        <input class="p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="prenom" name="prenom" value="<?php echo $_POST['prenom'];?>" disabled>
                    </div>
                    <div class="w-full">
                        <label class="text-small" for="nom">Nom*</label>
                        <input class="p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="nom" name="nom" value="<?php echo $_POST['nom'];?>" disabled>
                    </div>
                </div>
                
                <label class="text-small" for="mail">Adresse mail*</label>
                <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="email" id="mail" name="mail" value="<?php echo $_POST['mail'];?>" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$*" title="" disabled>
                
                <label class="text-small" for="username">Nom d'utilisateur*</label>
                <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="username" name="username" pattern="[a-zA-Z ]*" title="" required>
                
                <label class="text-small" for="adresse">Adresse postale*</label>
                <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="adresse" name="adresse" title="" required>
                
                <div class="flex flex-nowrap space-x-3 mb-1.5">
                    <div class="w-28">
                        <label class="text-small" for="code">Code postale*</label>
                        <input class="text-right p-2 bg-base100 w-28 h-12 rounded-lg" type="text" id="code" name="code" pattern="[a-zA-Z ]*" title="" required>
                    </div>
                    <div class="w-full">
                        <label class="text-small" for="ville">Ville*</label>
                        <input class="p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="ville" name="ville" pattern="[a-zA-Z ]*" title="" required>
                    </div>
                </div>

                <label class="text-small" for="tel">Téléphone*</label>
                <div class="w-full">
                    <input class="text-center p-2 bg-base100 w-36 h-12 mb-3 rounded-lg" type="tel" id="tel" name="tel" pattern="[a-zA-Z ]*" title="" required>
                </div>

                <div class="mb-3 flex items-start">
                    <input class="mt-0.5 mr-1.5" type="checkbox" name="example">
                    <label class="text-small">J’accepte les <u>conditions d'utilisation</u> et vous confirmez que vous avez lu notre <u>Politique de confidentialité et d'utilisation des cookies</u>.</label>
                </div>
                
                <input type="submit" value="Créer mon compte" class="cursor-pointer w-full h-12 bg-secondary text-white font-bold rounded-lg inline-flex items-center justify-center border border-transparent focus:scale-[0.97] hover:bg-orange-600 hover:border-orange-600 hover:text-white">
            </form>
        </div>
        <p class="w-full text-center sm:mt-8">Ce site est protégé par reCAPTCHA ; les <u>politiques de confidentialité</u> et les <u>conditions d'utilisation</u> de Google s'appliquent.</p>
    </body>
    </html>
<?php } ?>