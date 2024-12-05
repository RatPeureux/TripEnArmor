<?php
session_start(); // Démarre la session au début du script
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';

if (isConnectedAsPro()) {
    header('location: /pro');
    exit();
}

// Réinistialiser les messages d'erreur quand on arrive pour la première fois sur la page
if (!isset($_SESSION['data_en_cours_inscription'])) {
    unset($_SESSION['error']);
}

// 1ère étape de la création
if (!isset($_POST['mail']) && !isset($_GET['valid_mail'])) {

    // Effacer les messages d'erreur si on revient de l'étape 2 vers l'étape 1
    if (isset($_SESSION['data_en_cours_inscription']['num_tel'])) {
        $_SESSION['error'] = '';
    }
    // Utile car utiliée plusieurs fois
    $statut = isset($_SESSION['data_en_cours_inscription']['statut']) ? $_SESSION['data_en_cours_inscription']['statut'] : '';
    ?>

    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image" href="/public/images/favicon.png">
        <link rel="stylesheet" href="/styles/input.css">
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="/styles/config.js"></script>
        <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>

        <title>Création de compte - Professionnel - PACT</title>
    </head>

    <body class="h-screen bg-white p-4 overflow-hidden">
        <!-- Icône pour revenir à la page précédente -->
        <i onclick="history.back()" class="fa-solid fa-arrow-left fa-2xl cursor-pointer"></i>

        <div class="h-full flex flex-col items-center justify-center">
            <div class="relative w-full max-w-96 h-fit flex flex-col items-center justify-center sm:w-96 m-auto">
                <!-- Logo de l'application -->
                <a href="/" class="w-full">
                    <img class="relative mx-auto -top-8" src="/public/icones/logo.svg" alt="moine" width="108">
                </a>

                <form class="bg-base100 w-full p-5 rounded-lg border-2 border-secondary" action="" method="POST"
                    onsubmit="return validateForm()">
                    <p class="pb-3">Je créé un compte Professionnel</p>

                    <!-- Choix du statut de l'organisation -->
                    <label class="text-small" for="statut">Je suis un organisme&nbsp;</label>
                    <select class="text-small mt-1.5 mb-3 bg-white p-1 rounded-lg" id="statut" name="statut"
                        title="Sélécionner le statut de l'organisme (public OU privé)" onchange="updateLabel()" required>
                        <option value="" disabled <?php if ($statut == "")
                            echo 'selected' ?>> --- </option>
                            <option value="public" <?php if ($statut == "public")
                            echo 'selected'; ?>>public</option>
                        <option value="privé" <?php if ($statut == "privé")
                            echo 'selected' ?>>privé</option>
                        </select>
                        <br>

                        <!-- Champ pour le nom -->
                        <label class="text-small" for="nom" id="nom">Dénomination sociale / Nom de l'organisation</label>
                        <input class="p-2 bg-white w-full h-12 mb-1.5 rounded-lg" type="text" id="nom" name="nom"
                            title="Saisir le nom de l'organisation (max 100 caractères)" maxlength="100"
                            value="<?php echo $_SESSION['data_en_cours_inscription']['nom'] ?? '' ?>" required>

                    <!-- Champ pour l'adresse mail -->
                    <label class=" text-small" for="mail">Adresse mail</label>
                    <input class="p-2 bg-white w-full h-12 mb-1.5 rounded-lg" type="mail" id="mail" name="mail"
                        title="L'adresse mail doit comporter un '@' et un '.'" placeholder="exemple@gmail.com"
                        value="<?php echo $_SESSION['data_en_cours_inscription']['mail'] ?? '' ?>" required>
                    <!-- Message d'erreur pour l'adresse mail -->
                    <span class="error text-rouge-logo text-small"><?php echo $_SESSION['error'] ?? '' ?></span>

                    <!-- Champ pour le mot de passe -->
                    <div class="relative w-full">
                        <label class="text-small" for="mdp">Mot de passe</label>
                        <input class="p-2 pr-12 bg-white w-full h-12 mb-1.5 rounded-lg" type="password" id="mdp" name="mdp"
                            pattern="^(?=(.*[A-Z].*))(?=(.*\d.*))[\w\W]{8,}$"
                            title="Saisir un mot de passe valide (au moins 8 caractères dont 1 majuscule et 1 chiffre)"
                            value="<?php echo $_SESSION['data_en_cours_inscription']['mdp'] ?? '' ?>" required>
                        <!-- Icône pour afficher/masquer le mot de passe -->
                        <i class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer"
                            id="togglePassword1"></i>
                    </div>

                    <!-- Champ pour confirmer le mot de passe -->
                    <div class="relative w-full">
                        <label class="text-small" for="confMdp">Confirmer le mot de passe</label>
                        <input class="p-2 pr-12 bg-white w-full h-12 mb-1.5 rounded-lg" type="password" id="confMdp"
                            name="confMdp" pattern="^(?=(.*[A-Z].*))(?=(.*\d.*))[\w\W]{8,}$"
                            title="Confirmer le mot de passe saisit ci-dessus"
                            value="<?php echo $_SESSION['data_en_cours_inscription']['confMdp'] ?? '' ?>" required>
                        <!-- Icône pour afficher/masquer le mot de passe -->
                        <i class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer"
                            id="togglePassword2"></i>
                    </div>

                    <!-- Mots de passe ne correspondent pas -->
                    <span id="error-message" class="error text-rouge-logo text-small"></span>

                    <!-- Bouton pour continuer -->
                    <input type="submit" value="Continuer"
                        class="cursor-pointer w-full h-12 my-1.5 bg-secondary text-white font-bold rounded-lg inline-flex items-center justify-center border border-transparent focus:scale-[0.97] hover:bg-secondary/90 hover:border-secondary/90 hover:text-white">

                    <!-- Lien vers la page de connexion -->
                    <a href="/pro/connexion"
                        class="w-full h-12 p-1 bg-transparent text-secondary font-bold rounded-lg inline-flex items-center justify-center border border-secondary hover:text-white hover:bg-secondary/90 hover:border-secondary/90 focus:scale-[0.97]">
                        J'ai déjà un compte
                    </a>
                </form>
            </div>
        </div>
    </body>

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

        // Fonction pour mettre à jour le label en fonction du statut choisit
        function updateLabel() {
            const statut = document.getElementById('statut');
            const labelNom = document.getElementById('nom');

            if (statut) {
                if (statut.value === 'public') {
                    labelNom.textContent = 'Nom de l\'organisation';
                } else {
                    labelNom.textContent = 'Dénomination sociale';
                }
            }
        }
    </script>

    </html>


    <!-- 2ème étape de l'inscription -->
<?php } elseif (!isset($_POST['num_tel'])) {
    // Garder les informations remplies par l'utilisateur
    if (!empty($_POST)) {
        $_SESSION['data_en_cours_inscription'] = $_POST;
    }

    // Est-ce que cette adresse mail est déjà utilisée ?
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
    $stmt = $dbh->prepare("SELECT * FROM sae_db._compte WHERE email = :mail");
    $stmt->bindParam(":mail", $_POST['mail']);
    $stmt->execute();
    $result = $stmt->fetchAll();

    // Si il y a au moins un compte déjà avec cette adresse mail
    if (count($result) > 0) {
        $_SESSION['error'] = "Cette adresse mail est déjà utilisée";
        // Revenir sur sur l'inscription comme au début
        header("location: /pro/inscription");
    } elseif (!isset($_SESSION['data_en_cours_inscription']['num_tel'])) {
        $_SESSION['error'] = '';
    }

    // Variables utiles car souvent utilisées
    $statut = isset($_SESSION['data_en_cours_inscription']['statut']) ? $_SESSION['data_en_cours_inscription']['statut'] : '';
    ?>

    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="icon" type="image" href="/public/images/favicon.png">
        <link rel="stylesheet" href="/styles/input.css">
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="/styles/config.js"></script>
        <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>
        <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?libraries=places&amp;key=AIzaSyCzthw-y9_JgvN-ZwEtbzcYShDBb0YXwA8&language=fr" ></script>
        <script type="text/javascript" src="/scripts/autocomplete.js"></script>
        <script type="text/javascript" src="/scripts/formats.js" defer></script>

        <title>Création de compte - Professionnel - PACT</title>
    </head>

    <body class="h-screen bg-white pt-4 px-4 overflow-x-hidden">
        <!-- Icône pour revenir à la page précédente -->
        <i onclick="history.back()" class="fa-solid fa-arrow-left fa-2xl cursor-pointer"></i>

        <div class="w-full max-w-96 h-fit flex flex-col items-end sm:w-96 m-auto">
            <!-- Logo de l'application -->
            <a href="/" class="w-full">
                <img class="relative mx-auto -top-8" src="/public/icones/logo.svg" alt="moine" width="108">
            </a>

            <form class="mb-4 bg-base100 w-full p-5 rounded-lg border-2 border-secondary" action="" method="POST">
                <p class=" pb-3">Dites-nous en plus !</p>

                <div class="mb-3">
                    <label class="text-small" for="nom" id="nom">Je suis un organisme
                        <?php echo $statut ?></label>
                </div>

                <?php if ($statut == "privé") { ?>
                    <!-- Champ pour la dénomination sociale (en lecture seule) -->
                    <label class="text-small" for="nom" id="nom">Dénomination sociale</label>
                    <input class="p-2 text-gris bg-white w-full h-12 mb-1.5 rounded-lg" type="text" id="nom" name="nom"
                        title="Saisir votre dénomination sociale"
                        value="<?php echo $_SESSION['data_en_cours_inscription']['nom'] ?? '' ?>" readonly>
                <?php } else { ?>
                    <!-- Champ pour le nom de l'organisation (en lecture seule) -->
                    <label class="text-small" for="nom" id="nom">Nom de l'organisation</label>
                    <input class="p-2 text-gris bg-white w-full h-12 mb-1.5 rounded-lg" type="text" id="nom" name="nom"
                        title="Nom de l'organisation" value="<?php echo $_SESSION['data_en_cours_inscription']['nom'] ?? '' ?>"
                        readonly>
                <?php } ?>

                <!-- Champ pour l'adresse mail (en lecture seule) -->
                <label class="text-small" for="mail">Adresse mail</label>
                <input class="p-2 text-gris bg-white w-full h-12 mb-1.5 rounded-lg" type="email" id="mail" name="mail"
                    title="L'adresse mail doit comporter un '@' et un '.'" placeholder="exemple@gmail.com"
                    value="<?php echo $_SESSION['data_en_cours_inscription']['mail'] ?? '' ?>" readonly>

                <!-- Choix du type d'organisme public -->
                <?php if ($statut == 'public') {
                    $type_orga = isset($_SESSION['data_en_cours_inscription']['type_orga']) ? $_SESSION['data_en_cours_inscription']['type_orga'] : ''
                        ?>
                    <label class="text-small" for="type_orga">Je suis une&nbsp;</label>
                    <select class="text-small mt-1.5 mb-3 bg-white p-1 rounded-lg" id="type_orga" name="type_orga"
                        title="Choisir le type de l'organisme (association OU autre)" required>
                        <option value="" disabled <?php if ($type_orga == '')
                            echo 'selected'; ?>> --- </option>
                        <option value="public" <?php if ($type_orga == 'association')
                            echo 'selected'; ?>>association</option>
                        <option value="privé" <?php if ($type_orga == 'organisation autre')
                            echo 'selected'; ?>>organisation autre
                        </option>
                    </select>
                    <br>
                <?php } else { ?>
                    <!-- Inscription du numéro de SIREN -->
                    <label class="text-small" for="num_siren">Numéro SIRET</label>
                    <input class="p-2 bg-white w-full h-12 mb-1.5 rounded-lg" id="num_siren" name="num_siren"
                        pattern="^\d{3} \d{3} \d{3} \d{5}$" title="Le numéro SIRET doit être composé de 14 chiffres"
                        placeholder="Ex: 12345678901234"
                        value="<?php echo $_SESSION['data_en_cours_inscription']['num_siren'] ?? '' ?>" required>
                <?php } ?>

                <!-- Champs pour l'adresse -->
                <label class="text-small" for="adresse">Adresse</label>
                <input class="p-2 bg-white w-full h-12 mb-1.5 rounded-lg" type="text" id="user_input_autocomplete_address"
                    title="Saisir l'adresse de l'organisation" name="user_input_autocomplete_address"
                    placeholder="Ex : 10 Rue des Fleurs" maxlength="255"
                    value="<?php echo $_SESSION['data_en_cours_inscription']['user_input_autocomplete_address'] ?? '' ?>"
                    required>

                <label class="text-small" for="complement">Complément d'adresse</label>
                <input class="p-2 bg-white w-full h-12 mb-1.5 rounded-lg" type="text" id="complement" name="complement"
                    title="Saisir un complément d'adresse (facultatif)" maxlength="255"
                    placeholder="Bâtiment A, Appartement 5"
                    value="<?php echo $_SESSION['data_en_cours_inscription']['complement'] ?? '' ?>">

                <div class="flex flex-nowrap space-x-3 mb-1.5">
                    <div class="w-28">
                        <label class="text-small" for="postal_code">Code postal</label>
                        <input class="text-right p-2 bg-white w-28 h-12 rounded-lg" id="postal_code" name="postal_code"
                            pattern="^(0[1-9]|[1-8]\d|9[0-5]|2A|2B)\d{3}$" title="Format : 12345" placeholder="12345"
                            value="<?php echo $_SESSION['data_en_cours_inscription']['postal_code'] ?? '' ?>" required>
                    </div>
                    <div class="w-full">
                        <label class="text-small" for="locality">Ville</label>
                        <input class="p-2 bg-white w-full h-12 rounded-lg" id="locality" name="locality"
                            pattern="^[a-zA-Zéèêëàâôûç\-'\s]+(?:\s[A-Z][a-zA-Zéèêëàâôûç\-']+)*$" title="Saisir votre ville"
                            placeholder="Rennes"
                            value="<?php echo $_SESSION['data_en_cours_inscription']['locality'] ?? '' ?>" required>
                    </div>
                </div>

                <!-- Champ pour le numéro de téléphone -->
                <div class="w-full flex flex-col">
                    <label class="text-small" for="num_tel">Téléphone</label>
                    <input class="text-center p-2 bg-white w-36 h-12 mb-3 rounded-lg" id="num_tel" name="num_tel"
                        pattern="^0\d( \d{2}){4}"
                        title="Le numéro de téléphone doit commencer par un 0 et comporter 10 chiffres"
                        placeholder="01 23 45 67 89"
                        value="<?php echo $_SESSION['data_en_cours_inscription']['num_tel'] ?? '' ?>" required>
                </div>
                <!-- Message d'erreur pour le téléphone -->
                <?php
                if (isset($_GET['invalid_phone_number'])) { ?>
                    <span class="error text-rouge-logo text-small"><?php echo $_SESSION['error'] ?? '' ?></span>
                    <?php
                }
                ?>

                <?php if ($statut == "privé") { ?>
                    <!-- Choix de saisie des informations bancaires -->
                    <div class="group">
                        <div class="mb-1.5 flex items-start">
                            <input class="mt-0.5 mr-1.5" type="checkbox" id="plus" name="plus"
                                title="Accepter pour renseigner un IBAN ?" onchange="toggleIBAN()">
                            <label class="text-small" for="plus">Je souhaite saisir mes informations bancaires dès
                                maintenant
                                !</label>
                        </div>

                        <!-- Champ pour l'IBAN -->
                        <div id="iban-container" class="hidden">
                            <label class="text-small" for="iban">IBAN</label>
                            <input class="p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text" id="iban" name="iban"
                                pattern="^(FR)\d{2}( \d{4}){5} \d{3}$" title="Format : FRXX XXXX XXXX XXXX XXXX XXXX XXX"
                                placeholder="FRXX XXXX XXXX XXXX XXXX XXXX XXX"
                                value="<?php echo $_SESSION['data_en_cours_inscription']['iban'] ?? '' ?>">
                        </div>
                    </div>
                <?php } ?>

                <!-- Choix d'acceptation des termes et conditions -->
                <div class="mb-1.5 flex items-start">
                    <input class="mt-0.5 mr-1.5" type="checkbox" id="termes" name="termes" title="Accepter pour continuer"
                        required>
                    <label class="text-small" for="termes">J’accepte les <a href="/cgu" class="underline">Conditions
                            générales d'utilisation</a> et je confirme avoir lu la <a href="/pro/confidentialite_et_cookies"
                            class="underline">Politique de confidentialité et d'utilisation des cookies</a>.</label>
                    </label>
                </div>

                <!-- Bouton pour créer le compte -->
                <input type="submit" value="Créer mon compte"
                    class="cursor-pointer w-full mt-1.5 h-12 bg-secondary text-white font-bold rounded-lg inline-flex items-center justify-center border border-transparent focus:scale-[0.97] hover:bg-secondary/90 hover:border-secondary/90 hover:text-white">

                <!-- Garder les informations de POST même si les champs ne sont plus visibles -->
                <input type="hidden" name="statut"
                    value="<?php echo $_SESSION['data_en_cours_inscription']['statut'] ?? '' ?>">
                <input type="hidden" name="mdp" value="<?php echo $_SESSION['data_en_cours_inscription']['mdp'] ?? '' ?>">
                <input type="hidden" name="confMdp"
                    value="<?php echo $_SESSION['data_en_cours_inscription']['mdp'] ?? '' ?>">
            </form>
        </div>
    </body>

    <script>
        // Fonction pour afficher ou masquer le champ IBAN
        function toggleIBAN() {
            const checkbox = document.getElementById('plus');
            const ibanContainer = document.getElementById('iban-container');
            const iban = document.getElementById('iban');

            // Afficher ou masquer le conteneur IBAN
            ibanContainer.classList.toggle('hidden', !checkbox.checked);

            if (checkbox.checked) {
                iban.value = 'FR'; // Ajoute le préfixe 'FR'
                iban.disabled = false; // Active le champ
            } else {
                iban.value = ''; // Supprime toute saisie
                iban.disabled = true; // Désactive le champ
            }
        }
    </script>

    </html>

    <!-- 3ème étape de création (essayer d'insérer dans la base) -->
<?php } else {
    // Garder les informations remplies par l'utilisateur
    if (!empty($_POST)) {
        $_SESSION['data_en_cours_inscription'] = $_POST;
    }

    // Est-ce que le numéro de téléphone renseigné a déjà été utilisé ?
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
    $stmt = $dbh->prepare("SELECT * FROM sae_db._compte WHERE num_tel = :num_tel");
    $stmt->bindParam(":num_tel", $_POST['num_tel']);
    $stmt->execute();
    $result = $stmt->fetchAll();
    // Si il y a au moins un compte déjà avec ce numéro de téléphone
    if (count($result) > 0) {
        $_SESSION['error'] = "Ce numéro de téléphone est déjà utilisé";
        // Revenir sur sur l'inscription comme au début
        header("location: /pro/inscription?valid_mail=true&invalid_phone_number=true");
    }
    function extraireRibDepuisIban($iban)
    {
        // Supprimer les espaces
        $iban = str_replace(' ', '', $iban);

        $code_banque = substr($iban, 4, 5);
        $code_guichet = substr($iban, 9, 5);
        $numero_compte = substr($iban, 14, 11);
        $cle = substr($iban, 25, 2);

        return [
            'code_banque' => $code_banque,
            'code_guichet' => $code_guichet,
            'numero_compte' => $numero_compte,
            'cle' => $cle,
        ];
    }
    function extraireInfoAdresse($adresse)
    {
        // Utiliser une expression régulière pour extraire le numéro et l'odonyme
        if (preg_match('/^(\d+)\s+(.*)$/', $adresse, $matches)) {
            return [
                'numero' => $matches[1],
                'odonyme' => $matches[2],
            ];
        }

        // Si l'adresse ne correspond pas au format attendu, retourner des valeurs par défaut
        return [
            'numero' => '',
            'odonyme' => $adresse,
        ];
    }

    // Partie pour traiter la soumission du second formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['num_tel'])) {
        // Assurer que tous les champs obligatoires sont remplis
        $adresse = $_POST['user_input_autocomplete_address'];
        $infosSupAdresse = extraireInfoAdresse($adresse);
        $complement = $_POST['complement'];
        $code = $_POST['postal_code'];
        $ville = $_POST['locality'];

        // Exécuter la requête pour l'adresse
        $stmtAdresse = $dbh->prepare("INSERT INTO sae_db._adresse (code_postal, ville, numero, odonyme, complement) VALUES (:code, :ville, :numero, :odonyme, :complement)");
        $stmtAdresse->bindParam(':complement', $complement);
        $stmtAdresse->bindParam(':odonyme', $infosSupAdresse['odonyme']);
        $stmtAdresse->bindParam(':numero', $infosSupAdresse['numero']);
        $stmtAdresse->bindParam(':code', $code);
        $stmtAdresse->bindParam(':ville', $ville);

        if ($stmtAdresse->execute()) {
            $id_adresse = $dbh->lastInsertId();

            // Récupérer les information du compte à créer
            $statut = $_POST['statut'];
            $type_orga = $_POST['type_orga'];
            $num_siren = $_POST['num_siren'];
            $nom_pro = $_POST['nom'];
            $mail = $_POST['mail'];
            $mdp = $_POST['mdp'];
            $tel = $_POST['num_tel'];
            $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
            if (isset($_POST['iban'])) {
                $iban = $_POST['iban'];
            }

            // Préparer l'insertion dans la table _professionnel (séparer public / privé)
            if ($statut === "public") {
                $stmtProfessionnel = $dbh->prepare("INSERT INTO sae_db._pro_public (email, mdp_hash, num_tel, id_adresse, nom_pro, type_orga) VALUES (:mail, :mdp, :num_tel, :id_adresse, :nom_pro, :type_orga)");
                $stmtProfessionnel->bindParam(':type_orga', $type_orga);
                $stmtProfessionnel->bindParam(':nom_pro', $nom_pro);
                $stmtProfessionnel->bindParam(':mail', $mail);
                $stmtProfessionnel->bindParam(':mdp', $mdp_hash);
                $stmtProfessionnel->bindParam(':num_tel', $tel);
                $stmtProfessionnel->bindParam(':id_adresse', $id_adresse);

                // Exécuter la requête pour le professionnel
                $stmtProfessionnel->execute();
            } else {
                // Extraire les valeurs du RIB à partir de l'IBAN
                $id_rib = -1;
                if ($iban) {
                    $rib = extraireRibDepuisIban($iban);
                    $stmtRib = $dbh->prepare("INSERT INTO sae_db._rib (code_banque, code_guichet, numero_compte, cle) VALUES (:code_banque, :code_guichet, :numero_compte, :cle)");
                    $stmtRib->bindParam(':code_banque', $rib['code_banque']);
                    $stmtRib->bindParam(':code_guichet', $rib['code_guichet']);
                    $stmtRib->bindParam(':numero_compte', $rib['numero_compte']);
                    $stmtRib->bindParam(':cle', $rib['cle']);
                    if ($stmtRib->execute()) {
                        $id_rib = $dbh->lastInsertId();
                    }
                }

                $stmtProfessionnel = $dbh->prepare("INSERT INTO sae_db._pro_prive (email, mdp_hash, num_tel, id_adresse, nom_pro, num_siren" . ($id_rib != -1 ? ", id_rib" : "") . ") VALUES (:mail, :mdp, :num_tel, :id_adresse, :nom_pro, :num_siren" . ($id_rib != -1 ? ", :id_rib" : "") . ")");
                // Lier les paramètres pour le professionnel
                $stmtProfessionnel->bindParam(':num_siren', $num_siren);
                $stmtProfessionnel->bindParam(':nom_pro', $nom_pro);
                $stmtProfessionnel->bindParam(':mail', $mail);
                $stmtProfessionnel->bindParam(':mdp', $mdp_hash);
                $stmtProfessionnel->bindParam(':num_tel', $tel);
                $stmtProfessionnel->bindParam(':id_adresse', $id_adresse);
                if ($id_rib != -1) {
                    $stmtProfessionnel->bindParam(':id_rib', $id_rib);
                }

                // Exécuter la requête pour le professionnel
                $stmtProfessionnel->execute();
            }
        }
    }

    // Quand tout est bien réalisé, rediriger vers l'accueil du pro en étant connecté
    $_SESSION['id_pro'] = $$dbh->lastInsertId();
    unset($_SESSION['id_membre']);
    header("location: /pro");
} ?>