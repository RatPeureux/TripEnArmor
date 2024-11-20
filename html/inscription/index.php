<?php if (!isset($_POST['mail'])) { ?>

    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <link rel="icon" type="image" href="/public/images/favicon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image" href="/public/images/favicon.png">
        <link rel="stylesheet" href="/styles/output.css">
        <title>Création de compte 1/2</title>
        <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>
    </head>

    <body class="h-screen bg-white p-4 overflow-hidden">

        <!-- Icône pour revenir à la page précédente -->
        <i onclick="history.back()" class="fa-solid fa-arrow-left fa-2xl cursor-pointer"></i>

        <div class="h-full flex flex-col items-center justify-center">
            <div class="relative w-full max-w-96 h-fit flex flex-col items-center justify-center sm:w-96 m-auto">
                <!-- Logo de l'application -->
                <a href="/" class="w-full">
                    <img class="relative mx-auto -top-8" src="../public/images/logo.svg" alt="moine" width="108">
                </a>

                <form class="bg-base100 w-full p-5 rounded-lg border-2 border-primary" action="/inscription"
                    method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <p class="pb-3">Je créé un compte Membre</p>

                    <!-- Champs pour le prénom et le nom -->
                    <div class="flex flex-nowrap space-x-3 mb-1.5">
                        <div class="w-full">
                            <label class="text-small" for="prenom">Prénom</label>
                            <input class="p-2 bg-white w-full h-12 rounded-lg" type="text" id="prenom" name="prenom"
                                title="Saisir mon prénom" maxlength="50" required>
                        </div>
                        <div class="w-full">
                            <label class="text-small" for="nom">Nom</label>
                            <input class="p-2 bg-white w-full h-12 rounded-lg" type="text" id="nom" name="nom"
                                title="Saisir mon nom" maxlength="50" required>
                        </div>
                    </div>

                    <!-- Champ pour l'adresse mail -->
                    <label class="text-small" for="mail">Adresse mail</label>
                    <input class="p-2 bg-white w-full h-12 mb-1.5 rounded-lg" type="mail" id="mail" name="mail"
                        pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,}$" title="Saisir une adresse mail"
                        maxlength="255" required>

                    <!-- Champ pour le mot de passe -->
                    <div class="relative w-full">
                        <label class="text-small" for="mdp">Mot de passe</label>
                        <input class="p-2 pr-12 bg-white w-full h-12 mb-1.5 rounded-lg" type="password" id="mdp" name="mdp"
                            pattern=".*[A-Z].*.*\d.*|.*\d.*.*[A-Z].*" title="
                            • 8 caractères au moins
                            • 1 majuscule
                            • 1 chiffre" minlength="8" autocomplete="new-password" required>
                        <!-- Oeil pour afficher le mot de passe -->
                        <i class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer"
                            id="togglePassword1"></i>
                    </div>

                    <!-- Champ pour confirmer le mot de passe -->
                    <div class="relative w-full">
                        <label class="text-small" for="confMdp">Confirmer le mot de passe</label>
                        <input class="p-2 pr-12 bg-white w-full h-12 mb-1.5 rounded-lg" type="password" id="confMdp"
                            name="confMdp" pattern=".*[A-Z].*.*\d.*|.*\d.*.*[A-Z].*" title="
                            • 8 caractères au moins
                            • 1 majuscule
                            • 1 chiffre" minlength="8" autocomplete="new-password" required>
                        <!-- Oeil pour afficher le mot de passe -->
                        <i class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer"
                            id="togglePassword1"></i>
                    </div>

                    <!-- Messages d'erreurs -->
                    <span id="error-message" class="error text-rouge-logo text-small"></span>

                    <!-- Bouton pour continuer -->
                    <input type="submit" value="Continuer"
                        class="cursor-pointer w-full h-12 my-1.5 bg-primary text-white font-bold rounded-lg inline-flex items-center justify-center border border-transparent focus:scale-[0.97] hover:bg-orange-600 hover:border-orange-600 hover:text-white">

                    <!-- Lien vers la page de connexion -->
                    <a href="/connexion"
                        class="w-full h-12 p-1 bg-transparent text-primary font-bold rounded-lg inline-flex items-center justify-center border border-primary hover:text-white hover:bg-orange-600 hover:border-orange-600 focus:scale-[0.97]">
                        J'ai déjà un compte
                    </a>
                </form>
            </div>
        </div>
    </body>

    </html>

    <script>
        // Gestion des icônes pour afficher/masquer le mot de passe
        const togglePassword1 = document.getElementById('togglePassword1');
        const togglePassword2 = document.getElementById('togglePassword2');
        const mdp = document.getElementById('mdp');
        const confMdp = document.getElementById('confMdp');

        if (togglePassword1) {
            togglePassword1.addEventListener('mousedown', function () {
                mdp.type = 'text'; // Change le type d'input pour afficher le mot de passe
                this.classList.remove('fa-eye'); // Change l'icône
                this.classList.add('fa-eye-slash');
            });
            togglePassword1.addEventListener('mouseup', function () {
                mdp.type = 'password'; // Masque le mot de passe à nouveau
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            });
        }

        if (togglePassword2) {
            togglePassword2.addEventListener('mousedown', function () {
                confMdp.type = 'text'; // Change le type d'input pour afficher le mot de passe
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            });
            togglePassword2.addEventListener('mouseup', function () {
                confMdp.type = 'password'; // Masque le mot de passe à nouveau
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            });
        }

        // Fonction de validation du formulaire
        function validateForm() {
            var mdp = document.getElementById("mdp").value;
            var confMdp = document.getElementById("confMdp").value;
            var errorMessage = document.getElementById("error-message");

            // Vérifie si les mots de passe correspondent
            if (mdp !== confMdp) {
                errorMessage.textContent = "Les mots de passe ne correspondent pas."; // Affiche un message d'erreur
                return false; // Empêche l'envoi du formulaire
            }

            errorMessage.textContent = ""; // Réinitialise le message d'erreur
            return true; // Permet l'envoi du formulaire
        }
    </script>

<?php } elseif (isset($_POST['mail']) && !isset($_POST['num_tel'])) {

    // Si le formulaire a été soumis
    $prenom = str_contains($_POST['prenom'], "-") ? ucfirst(strtolower(strstr($_POST['prenom'], '-', true))) . "-" . ucfirst(strtolower(substr(strstr($_POST['prenom'], '-'), 1))) : ucfirst(strtolower($_POST['prenom']));
    $nom = strtoupper($_POST['nom']);
    $mail = strtolower($_POST['mail']);
    $mdp = $_POST['mdp'];
    ?>

    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image" href="/public/images/favicon.png">
        <link rel="stylesheet" href="/styles/output.css">
        <title>Création de compte 2/2</title>
        <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>
        <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?libraries=places&amp;key=AIzaSyCzthw-y9_JgvN-ZwEtbzcYShDBb0YXwA8&language=fr "></script>
        <script type="text/javascript" src="/scripts/autocomplete.js"></script>
    </head>

    <body class="h-screen bg-white pt-4 px-4 overflow-x-hidden">
        <!-- Icône pour revenir à la page précédente -->
        <i onclick="history.back()" class="absolute top-7 fa-solid fa-arrow-left fa-2xl cursor-pointer"></i>

        <div class="w-full max-w-96 h-fit flex flex-col items-end sm:w-96 m-auto">
            <!-- Logo de l'application -->
            <img class="text mb-4" src="/public/images/logo.svg" alt="moine" width="57">

            <form class="mb-4 bg-base100 w-full p-5 rounded-lg border-2 border-primary" action="/inscription" method="post" enctype="multipart/form-data">
                <p class="pb-3">Dites-nous en plus !</p>

                <!-- Champs pour le prénom et le nom (en lecture seule) -->
                <div class="flex flex-nowrap space-x-3 mb-1.5">
                    <div class="w-full">
                        <label class="text-small" for="prenom">Prénom</label>
                        <input class="p-2 text-gris bg-white w-full h-12 rounded-lg" type="text" id="prenom" name="prenom"
                            title="Mon prénom" value="<?php echo $prenom; ?>" readonly>
                    </div>
                    <div class="w-full">
                        <label class="text-small" for="nom">Nom</label>
                        <input class="p-2 text-gris bg-white w-full h-12 rounded-lg" type="text" id="nom" name="nom"
                            title="Mon nom" value="<?php echo $nom; ?>" readonly>
                    </div>
                </div>

                <!-- Champ pour l'adresse mail (en lecture seule) -->
                <label class="text-small" for="mail">Adresse mail</label>
                <input class="p-2 text-gris bg-white w-full h-12 mb-1.5 rounded-lg" type="email" id="mail" name="mail"
                    title="Mon adresse mail" value="<?php echo $mail; ?>" readonly>

                <!-- Champ pour le pseudonyme -->
                <label class="text-small" for="pseudo">Pseudonyme</label>
                <input class="p-2 bg-white w-full h-12 mb-1.5 rounded-lg" type="text" id="pseudo" name="pseudo"
                    maxlength="16" required>

                <!-- Champs pour l'adresse -->
                <label class="text-small" for="user_input_autocomplete_address">Adresse postale</label>
                <input class="p-2 bg-white w-full h-12 mb-1.5 rounded-lg" type="text" id="user_input_autocomplete_address"
                    name="user_input_autocomplete_address" title="ex: 2 rue Saint-Jean OU rue Edouard Branly"
                    maxlength="255" required>

                <label class="text-small" for="complement">Complément d'adresse postale</label>
                <input class="p-2 bg-white w-full h-12 mb-1.5 rounded-lg" type="text" id="complement" name="complement"
                    title="Complément d'adresse" maxlength="255">

                <div class="flex flex-nowrap space-x-3 mb-1.5">
                    <div class="w-28">
                        <label class="text-small" for="code">Code postal</label>
                        <input class="text-right p-2 bg-white w-28 h-12 rounded-lg" type="text" id="code"
                            name="code" pattern="^(0[1-9]|[1-8]\d|9[0-5]|2A|2B)\d{3}$" title="Saisir mon code postal"
                            minlength="5" maxlength="5" oninput="number(this)" required>
                    </div>
                    <div class="w-full">
                        <label class="text-small" for="ville">Ville</label>
                        <input class="p-2 bg-white w-full h-12 rounded-lg" type="text" id="ville" name="ville"
                            pattern="^[a-zA-Zéèêëàâôûç\-'\s]+(?:\s[A-Z][a-zA-Zéèêëàâôûç\-']+)*$" title="Saisir ma ville"
                            maxlength="50" required>
                    </div>
                </div>

                <!-- Champ pour le numéro de téléphone -->
                <div class="w-full flex flex-col">
                    <label class="text-small" for="num_tel">Téléphone</label>
                    <input class="text-center p-2 bg-white w-36 h-12 mb-3 rounded-lg" type="tel" id="num_tel" name="num_tel"
                        pattern="^0\d( \d{2}){4}" title="Saisir un numéro de téléphone" minlength="14" maxlength="14"
                        oninput="formatTEL(this)" required>
                </div>

                <!-- Choix d'acceptation des termes et conditions -->
                <div class="mb-1.5 flex items-start">
                    <input class="mt-0.5 mr-1.5" type="checkbox" id="termes" name="termes" title="Accepter pour continuer"
                        required>
                    <label class="text-small" for="termes">J’accepte les <u class="cursor-pointer">conditions
                            d'utilisation</u> et vous confirmez que vous avez lu notre <u class="cursor-pointer">Politique
                            de confidentialité et d'utilisation des cookies</u>.</label>
                </div>

                <!-- Messages d'erreurs -->
                <span id="error-message" class="error text-rouge-logo text-small"></span>

                <!-- Bouton pour créer le compte -->
                <input type="submit" value="Créer mon compte"
                    class="mt-1.5 cursor-pointer w-full h-12 bg-primary text-white font-bold rounded-lg inline-flex items-center justify-center border border-transparent focus:scale-[0.97] hover:bg-orange-600 hover:border-orange-600 hover:text-white">

                <input type="hidden" name="mdp" value="<?php echo htmlspecialchars($mdp); ?>">
            </form>
        </div>
    </body>

    </html>

    <script>
        // Fonction pour autoriser uniquement les chiffres dans l'input
        function number(input) {
            let value = input.value.replace(/[^0-9]/g, '');
            input.value = value;
        }

        // Fonction pour formater le numéro de téléphone
        function formatTEL(input) {
            let value = input.value.replace(/[^0-9]/g, '');
            const formattedValue = value.match(/.{1,2}/g)?.join(' ') || ''; // Formatage en paires de chiffres
            input.value = formattedValue;
        }
    </script>

<?php } else {

    ob_start();
    // Connexion avec la bdd
    include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

    $message = ''; // Initialiser le message

    // Partie pour traiter la soumission du second formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['num_tel'])) {
        // Assurer que tous les champs obligatoires sont remplis
        $prenom = $_POST['prenom'];
        $nom = $_POST['nom'];
        $mail = $_POST['mail'];
        $mdp = $_POST['mdp']; // Récupérer le mot de passe du champ caché
        $pseudo = $_POST['pseudo'];
        $adresse = $_POST['adresse'];
        $complement = $_POST['complement'];
        $code = $_POST['code'];
        $ville = $_POST['ville'];
        $tel = $_POST['num_tel'];

        function extraireInfoAdresse($adresse)
        {
            $numero = substr($adresse, 0, 1);
            $odonyme = substr($adresse, 2);

            return [
                'numero' => $numero,
                'odonyme' => $odonyme,
            ];
        }

        // Hachage du mot de passe
        $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

        try {
            $test = extraireInfoAdresse($adresse);
            $stmtTest = $dbh->prepare("INSERT INTO sae_db._adresse (code_postal, ville, numero, odonyme, complement_adresse) VALUES (:code, :ville, :numero, :odonyme, :complement)");
            $stmtTest->bindParam(':code', $code);
            $stmtTest->bindParam(':ville', $ville);
            $stmtTest->bindParam(':numero', $test['numero']);
            $stmtTest->bindParam(':odonyme', $test['odonyme']);
            $stmtTest->bindParam(':complement', $complement); // Assurez-vous que id_compte est défini
            // Récupérer l'ID de l'adresse insérée
            $id_adresse = $dbh->lastInsertId();

            if ($stmtTest->execute()) {
                $message = "Votre compte a bien été créé. Vous allez maintenant être redirigé vers la page de connexion.";
            } else {
                $message = "Erreur lors de l'insertion dans la table RIB : " . implode(", ", $stmtTest->errorInfo());
            }
        } catch (Exception $e) {
            $message = "Erreur lors de l'extraction des données RIB : " . $e->getMessage();
        }

        // Exécuter la requête pour l'adresse
        if ($stmtTest->execute()) {
            // Préparer l'insertion dans la table Membre
            $stmtMembre = $dbh->prepare("INSERT INTO sae_db._membre (email, mdp_hash, num_tel, id_adresse, pseudo, nom, prenom) VALUES (:mail, :mdp, :num_tel, :id_adresse, :pseudo :nom, :prenom)");
            $stmtMembre->bindParam(':mail', $mail);
            $stmtMembre->bindParam(':mdp', $mdp_hash);
            $stmtMembre->bindParam(':num_tel', $tel);
            $stmtMembre->bindParam(':pseudo', $pseudo);
            $stmtMembre->bindParam(':nom', $nom);
            $stmtMembre->bindParam(':prenom', $prenom);
            $stmtMembre->bindParam(':id_adresse', $id_adresse);

            // Exécuter la requête pour le membre
            if ($stmtMembre->execute()) {
                $message = "Votre compte a bien été créé. Vous allez maintenant être redirigé vers la page de connexion.";
            } else {
                $message = "Erreur lors de la création du compte : " . implode(", ", $stmtMembre->errorInfo());
            }
        }
    }

    ob_end_flush();

    // Quand tout est bien réalisé, rediriger au login avec la confimation de création du compte
    header("location: /connexion?created=1");

} ?>