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
                <form class="bg-base200 w-full p-5 rounded-lg border-2 border-secondary" action="create-pro.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <p class="pb-3">Je créé un compte Professionnel</p>

                    <label class="text-small" for="denom">Dénomination sociale*</label>
                    <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="denom" name="denom" pattern="^?:(\w+|\w+[\.\-_]?\w+)+$" title="La dénomination sociale de votre entreprise." maxlength="100" required>
                    
                    <label class="text-small" for="mail">Adresse mail*</label>
                    <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="email" id="mail" name="mail" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="exemple@mail.com" maxlength="255" required>
                    
                    <label class="text-small" for="passwd">Mot de passe*</label>
                    <input class="p-2 bg-base100 w-full h-12 mb-3 rounded-lg" type="password" id="passwd" name="passwd" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?&quot;:{}|&lt;&gt;])[A-Za-z\d!@#$%^&*(),.?&quot;:{}|&gt;&lt;]{8,}" autocomplete="new-password" required>

                    <label class="text-small" for="passwd-conf">Confirmer le mot de passe*</label>
                    <input class="p-2 bg-base100 w-full h-12 mb-3 rounded-lg" type="password" id="passwd-conf" name="passwd-conf" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?&quot;:{}|&lt;&gt;])[A-Za-z\d!@#$%^&*(),.?&quot;:{}|&gt;&lt;]{8,}" autocomplete="new-password" required>

                    <span id="error-message" class="error text-rouge-logo text-small"></span>

                    <input type="submit" value="Continuer" class="cursor-pointer w-full h-12 mb-1.5 bg-secondary text-white font-bold rounded-lg inline-flex items-center justify-center border border-transparent focus:scale-[0.97] hover:bg-green-900 hover:border-green-900 hover:text-white">
                    <a href="login-pro.html" class="w-full h-12 p-1 bg-transparent text-secondary font-bold rounded-lg inline-flex items-center justify-center border border-secondary hover:text-white hover:bg-secondary hover:border-secondary focus:scale-[0.97]"> 
                        J'ai déjà un compte
                    </a>
                </form>
            </div>
        </div>
    </body>
    </html>

    <script>
    function validateForm() {
        var passwd = document.getElementById("passwd").value;
        var passwdConf = document.getElementById("passwdConf").value;
        var errorMessage = document.getElementById("error-message");

        if (passwd !== passwdConf) {
            errorMessage.textContent = "Les mots de passe ne correspondent pas.";
            return false;
        }
        
        errorMessage.textContent = "";
        return true;
    }
    </script>
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
            <form class="mb-4 bg-base200 w-full p-5 rounded-lg border-2 border-secondary" action="" method="post" enctype="multipart/form-data">
                <p class="pb-3">Dites-nous en plus !</p>

                <label class="text-small" for="denom">Dénomination sociale</label>
                <input class="p-2 text-gris bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="denom" name="denom" value="<?php echo $_POST['denom'];?>" readonly>
                
                <label class="text-small" for="mail">Adresse mail</label>
                <input class="p-2 text-gris bg-base100 w-full h-12 mb-1.5 rounded-lg" type="email" id="mail" name="mail" value="<?php echo strtolower($_POST['mail']);?>" readonly>

                <label class="text-small" for="statut">Je suis un organisme&nbsp;</label>
                <select class="my-1.5 bg-base100 p-1 rounded-lg" id="statut" name="statut" required>
                    <option value="" disabled selected> --- </option>
                    <option value="public">public</option>
                    <option value="private">privé</option>
                </select><br>
                
                <label class="text-small" for="adresse">Adresse postale*</label>
                <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="adresse" name="adresse" pattern="\d{1,5}\s[\w\s.-]+$" maxlength="255" required>
                
                <div class="flex flex-nowrap space-x-3 mb-1.5">
                    <div class="w-28">
                        <label class="text-small" for="code">Code postal*</label>
                        <input class="text-right p-2 bg-base100 w-28 h-12 rounded-lg" type="text" id="code" name="code" pattern="^(0[1-9]|[1-8]\d|9[0-5]|2A|2B)[0-9]{3}$" title="Code postal non reconnu ! 5 chiffres obligatoires." maxlength="5" required>
                    </div>
                    <div class="w-full">
                        <label class="text-small" for="ville">Ville*</label>
                        <input class="p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="ville" name="ville" pattern="^[A-Z][a-zA-Zéèêëàâôûç\-'\s]+(?:\s[A-Z][a-zA-Zéèêëàâôûç\-']+)*$" maxlength="50" required>
                    </div>
                </div>

                <label class="text-small" for="tel">Téléphone*</label>
                <div class="w-full">
                    <input class="text-center p-2 bg-base100 w-36 h-12 mb-3 rounded-lg" type="tel" id="tel" name="tel" pattern="0[1-9]([-. ]?[0-9]{2}){4}" title="Seuls formats acceptés : sans espaces ou espacés par (. - ou `espace`)" maxlength="15" required>
                </div>
                <div class="group">
                    <div class="mb-1.5 flex items-start">
                        <input class="mt-0.5 mr-1.5" type="checkbox" name="iban+rib">
                        <label class="text-small">Je souhaite saisir mes informations bancaires dès maitenant !</u></label>
                    </div>

                    <div class="hidden group-has-[:checked]:block">
                        <label class="text-small" for="iban">IBAN</label>
                        <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="iban" name="iban" title="" maxlength="">
                    
                        <label class="text-small">RIB</label>
                        <div class="flex flex-nowrap space-x-3 mb-3">
                            <div class="w-full">
                                <label class="text-small" for="rib-banque">Banque</label><br>
                                <input class="text-center p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="rib-banque" name="rib-banque" pattern="" title="" maxlength="">
                            </div>
                            <div class="w-full">
                                <label class="text-small" for="rib-cle">Clé</label><br>
                                <input class="text-center p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="rib-cle" name="rib-cle" pattern="" title="" maxlength="">
                            </div>
                            <div class="w-full">
                                <label class="text-small" for="rib-guichet">Guichet</label><br>
                                <input class="text-center p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="rib-guichet" name="rib-guichet" pattern="" title="" maxlength="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 flex items-start">
                    <input class="mt-0.5 mr-1.5" type="checkbox" name="conditions" required>
                    <label class="text-small">J’accepte les <u>conditions d'utilisation</u> et vous confirmez que vous avez lu notre <u>Politique de confidentialité et d'utilisation des cookies</u>.</label>
                </div>
                
                <input type="submit" value="Créer mon compte" class="cursor-pointer w-full h-12 bg-secondary text-white font-bold rounded-lg inline-flex items-center justify-center border border-transparent focus:scale-[0.97] hover:bg-green-900 hover:border-green-900 hover:text-white">
            </form>
        </div>
    </body>
    </html>
<?php } ?>