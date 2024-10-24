<?php if (!isset($_POST['mail'])) { ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Lien vers le favicon de l'application -->
    <link rel="icon" type="image" href="/public/images/favicon.png">
    <!-- Lien vers le fichier CSS pour le style de la page -->
    <link rel="stylesheet" href="/styles/output.css">
    <title>Création de compte 1/2</title>
    <!-- Inclusion de Font Awesome pour les icônes -->
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>

</head>
<body class="h-screen bg-white p-4 overflow-hidden">
    <!-- Icône pour revenir à la page précédente -->
    <i onclick="history.back()" class="fa-solid fa-arrow-left fa-2xl cursor-pointer"></i>

    <div class="h-full flex flex-col items-center justify-center">
        <div class="relative w-full max-w-96 h-fit flex flex-col items-center justify-center sm:w-96 m-auto">
            <!-- Logo de l'application -->
            <img class="absolute -top-24" src="/public/images/logo.svg" alt="moine" width="108">

            <form class="bg-base100 w-full p-5 rounded-lg border-2 border-secondary" action="create-pro.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <p class="pb-3">Je créé un compte Professionnel</p>

                <!-- Choix du statut de l'utilisateur -->
                <label class="text-small" for="statut">Je suis un organisme&nbsp;</label>
                <select class="text-small mt-1.5 mb-3 bg-white p-1 rounded-lg" id="statut" name="statut" title="Choisir un statut" onchange="updateLabel()" required>
                    <option value="" disabled selected> --- </option>
                    <option value="public">public</option>
                    <option value="privé">privé</option>
                </select>
                <label class="text-small" for="statut">&nbsp;.</label></br>

                <!-- Champ pour le nom -->
                <label class="text-small" for="nom" id="nom">Dénomination sociale / Nom de l'organisation</label>
                <input class="p-2 bg-white w-full h-12 mb-1.5 rounded-lg" type="text" id="nom" name="nom" 
                       pattern="^?:(\w+|\w+[\.\-_]?\w+)+$" 
                       title="Saisir le nom de l'entreprise" maxlength="100" required>
                
                <!-- Champ pour l'adresse mail -->
                <label class="text-small" for="mail">Adresse mail</label>
                <input class="p-2 bg-white w-full h-12 mb-1.5 rounded-lg" type="email" id="mail" name="mail" 
                       pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" 
                       title="Saisir une adresse mail" maxlength="255" required>
                
                <!-- Champ pour le mot de passe -->
                <label class="text-small" for="mdp">Mot de passe</label>
                <div class="relative w-full">
                    <input class="p-2 pr-12 bg-white w-full h-12 mb-1.5 rounded-lg" type="password" id="mdp" name="mdp" 
                           pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?&quot;:{}|&lt;&gt;])[A-Za-z\d!@#$%^&*(),.?&quot;:{}|&gt;&lt;]{8,}" 
                           title="Saisir un mot de passe" minlength="8" autocomplete="new-password" required>
                    <!-- Icône pour afficher/masquer le mot de passe -->
                    <i class="fa-regular fa-eye fa-lg absolute top-6 right-4 cursor-pointer" id="togglePassword1"></i>
                </div>

                <!-- Champ pour confirmer le mot de passe -->
                <label class="text-small" for="confMdp">Confirmer le mot de passe</label>
                <div class="relative w-full">
                    <input class="p-2 pr-12 bg-white w-full h-12 mb-1.5 rounded-lg" type="password" id="confMdp" name="confMdp" 
                           pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?&quot;:{}|&lt;&gt;])[A-Za-z\d!@#$%^&*(),.?&quot;:{}|&gt;&lt;]{8,}" 
                           title="Saisir le même mot de passe" minlength="8" autocomplete="new-password" required>
                    <i class="fa-regular fa-eye fa-lg absolute top-6 right-4 cursor-pointer" id="togglePassword2"></i>
                </div>

                <!-- Messages d'erreurs -->
                <span id="error-message" class="error text-rouge-logo text-small"></span>

                <!-- Bouton pour continuer -->
                <input type="submit" value="Continuer" class="cursor-pointer w-full h-12 my-1.5 bg-secondary text-white font-bold rounded-lg inline-flex items-center justify-center border border-transparent focus:scale-[0.97] hover:bg-green-900 hover:border-green-900 hover:text-white">
                
                <!-- Lien vers la page de connexion -->
                <a href="login-pro.php" class="w-full h-12 p-1 bg-transparent text-secondary font-bold rounded-lg inline-flex items-center justify-center border border-secondary hover:text-white hover:bg-green-900 hover:border-green-900 focus:scale-[0.97]"> 
                    J'ai déjà un compte
                </a>
            </form>
        </div>
    </div>
</body>
</html>

<?php } elseif (isset($_POST['mail']) && !isset($_POST['num_tel'])) {

// Si le formulaire a été soumis
$statut = $_POST['statut'];
$nom = $_POST['nom'];
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
</head>
<body class="h-screen bg-white pt-4 px-4 overflow-x-hidden">
    <!-- Icône pour revenir à la page précédente -->
    <i onclick="history.back()" class="absolute top-7 fa-solid fa-arrow-left fa-2xl cursor-pointer"></i>

    <div class="w-full max-w-96 h-fit flex flex-col items-end sm:w-96 m-auto">
        <!-- Logo de l'application -->
        <img class="text mb-4" src="/public/images/logo.svg" alt="moine" width="57">

        <form class="mb-4 bg-base100 w-full p-5 rounded-lg border-2 border-secondary" action="create-pro.php" method="post" enctype="multipart/form-data"">
            <p class="pb-3">Dites-nous en plus !</p>

            <div class="mb-3">
                <label class="text-small" for="nom" id="nom">Je suis un organisme <?php echo $statut;?>.</label>
            </div>

            <?php if ($statut == "privé") { ?>
                <!-- Champ pour la dénomination sociale (en lecture seule) -->
                <label class="text-small" for="nom" id="nom">Dénomination sociale</label>
                <input class="p-2 text-gris bg-white w-full h-12 mb-1.5 rounded-lg" type="text" id="nom" name="nom" title="Dénomination sociale" value="<?php echo $nom;?>" readonly>
            <?php } else { ?>
                <!-- Champ pour le nom de l'organisation (en lecture seule) -->
                <label class="text-small" for="nom" id="nom">Nom de l'organisation</label>
                <input class="p-2 text-gris bg-white w-full h-12 mb-1.5 rounded-lg" type="text" id="nom" name="nom" title="Nom de l'organisation" value="<?php echo $nom;?>" readonly>
            <?php } ?>
            
            <!-- Champ pour l'adresse mail (en lecture seule) -->
            <label class="text-small" for="mail">Adresse mail</label>
            <input class="p-2 text-gris bg-white w-full h-12 mb-1.5 rounded-lg" type="email" id="mail" name="mail" title="Adresse mail" value="<?php echo $mail;?>" readonly>
            
            <!-- Champs pour l'adresse -->
            <label class="text-small" for="adresse">Adresse postale</label>
            <input class="p-2 bg-white w-full h-12 mb-1.5 rounded-lg" type="text" id="adresse" name="adresse" 
                   pattern="\d{1,5}\s[\w\s.-]+$" title="Saisir une adresse postale" maxlength="255" required>
            
            <label class="text-small" for="complement">Complément d'adresse postale</label>
            <input class="p-2 bg-white w-full h-12 mb-1.5 rounded-lg" type="text" id="complement" name="complement" title="Complément d'adresse" maxlength="255">
            
            <div class="flex flex-nowrap space-x-3 mb-1.5">
                <div class="w-28">
                    <label class="text-small" for="code">Code postal</label>
                    <input class="text-right p-2 bg-white w-28 h-12 rounded-lg" type="text" id="code" name="code" 
                           pattern="^(0[1-9]|[1-8]\d|9[0-5]|2A|2B)[0-9]{3}$" title="Saisir un code postal" minlength="5" maxlength="5" oninput="number(this)" required>
                </div>
                <div class="w-full">
                    <label class="text-small" for="ville">Ville</label>
                    <input class="p-2 bg-white w-full h-12 rounded-lg" type="text" id="ville" name="ville" 
                           pattern="^[a-zA-Zéèêëàâôûç\-'\s]+(?:\s[A-Z][a-zA-Zéèêëàâôûç\-']+)*$" title="Saisir une ville" maxlength="50" required>
                </div>
            </div>

            <!-- Champ pour le numéro de téléphone -->
            <label class="text-small" for="num_tel">Téléphone<span class="text-red-500"> *</span></label>
            <div class="w-full">
                <input class="text-center p-2 bg-white w-36 h-12 mb-3 rounded-lg" type="tel" id="num_tel" name="num_tel" 
                       pattern="^0\d( \d{2}){4}" title="Saisir un numéro de téléphone" minlength="14" maxlength="14" oninput="formatTEL(this)" required>
            </div>

            <?php if ($statut=="privé") { ?>
                <!-- Choix de saisie des informations bancaires -->
                <div class="group">
                    <div class="mb-1.5 flex items-start">
                        <input class="mt-0.5 mr-1.5" type="checkbox" id="plus" name="plus" onchange="toggleIBAN()">
                        <label class="text-small" for="plus">Je souhaite saisir mes informations bancaires dès maintenant !</label>
                    </div>

                    <!-- Champ pour l'IBAN -->
                    <div id="iban-container" class="hidden">
                        <label class="text-small" for="iban">IBAN</label>
                        <input class="p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text" id="iban" name="iban" 
                            pattern="^(FR)\d{2}( \d{4}){5} \d{3}$" title="Saisir un IBAN (FR)" minlength="33" maxlength="33" 
                            oninput="formatIBAN(this)" disabled>
                    </div>
                </div>  
            <?php } ?>

            <!-- Choix d'acceptation des termes et conditions -->
            <div class="mb-1.5 flex items-start">
                <input class="mt-0.5 mr-1.5" type="checkbox" id="termes" name="termes" title="Accepter pour continuer" required>
                <label class="text-small" for="termes">J’accepte les <u class="cursor-pointer">conditions d'utilisation</u> et vous confirmez que vous avez lu notre <u class="cursor-pointer">Politique de confidentialité et d'utilisation des cookies</u>.<span class="text-red-500"> *</span></label>
            </div>

            <!-- Messages d'erreurs -->
            <span id="error-message" class="error text-rouge-logo text-small"></span>
            
            <!-- Bouton pour créer le compte -->
            <input type="submit" value="Créer mon compte" class="cursor-pointer w-full mt-1.5 h-12 bg-secondary text-white font-bold rounded-lg inline-flex items-center justify-center border border-transparent focus:scale-[0.97] hover:bg-green-900 hover:border-green-900 hover:text-white">
            
            <input type="hidden" name="statut" value="<?php echo $statut; ?>">
            <input type="hidden" name="mdp_test" value="<?php echo htmlspecialchars($mdp); ?>">
        </form>
    </div>
</body>
</html>

<?php } else {

ob_start();
include('/php/connect_params.php');

$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Gère les erreurs de PDO

// Alteration de la table pour s'assurer que numero_compte est un VARCHAR
try {
    $dbh->exec("ALTER TABLE sae_db._rib ALTER COLUMN numero_compte TYPE VARCHAR(11)");
} catch (PDOException $e) {
    // Ignorer l'erreur si la colonne est déjà au bon type
    if ($e->getCode() !== '42P07') {
        // 42P07 est le code d'erreur pour une table ou colonne déjà existante
        throw $e;
    }
}

$message = ''; // Initialiser le message

function extraireRibDepuisIban($iban) {
    // Supprimer les espaces
    $iban = str_replace(' ', '', $iban);

    $code_banque = substr($iban, 5, 5);
    $code_guichet = substr($iban, 10, 5);
    $numero_compte = substr($iban, 15, 11);
    $cle_rib = substr($iban, 26, 2);

    return [
        'code_banque' => $code_banque,
        'code_guichet' => $code_guichet,
        'numero_compte' => $numero_compte,
        'cle_rib' => $cle_rib,
    ];
}

// Partie pour traiter la soumission du second formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['num_tel'])) {
    // Assurer que tous les champs obligatoires sont remplis
    $statut = $_POST['statut'];
    $nom = $_POST['nom'];
    $mail = $_POST['mail'];
    $mdp = $_POST['mdp'];
    $adresse = $_POST['adresse'];
    $complement = $_POST['complement'];
    $code = $_POST['code'];
    $ville = $_POST['ville'];
    $tel = $_POST['num_tel'];
    if (isset($_POST['iban'])) {
        $iban = $_POST['iban'];
    }

    // Hachage du mot de passe
    $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

    // Insérer dans la base de données pour l'adresse
    $stmtAdresse = $dbh->prepare("INSERT INTO sae_db._adresse (complement_adresse, code_postal, ville) VALUES (:adresse, :code, :ville)");

    // Lier les paramètres pour l'adresse
    $stmtAdresse->bindParam(':adresse', $adresse);
    $stmtAdresse->bindParam(':code', $code);
    $stmtAdresse->bindParam(':ville', $ville);
    
    if ($stmtAdresse->execute()) {
        // Récupérer l'ID de l'adresse insérée
        $adresseId = $dbh->lastInsertId();

        // Préparer l'insertion dans la table Professionnel
        if ($statut === "public") {
            $stmtProfessionnel = $dbh->prepare("INSERT INTO sae_db._pro_public (email, mdp_hash, num_tel, adresse_id, nom_orga) VALUES (:mail, :mdp, :num_tel, :adresse_id, :nom)");
        } else {

        }

        // Lier les paramètres pour le professionnel
        $stmtProfessionnel->bindParam(':nom', $nom);
        $stmtProfessionnel->bindParam(':mail', $mail);
        $stmtProfessionnel->bindParam(':mdp', $mdp_hash);
        $stmtProfessionnel->bindParam(':num_tel', $tel);
        $stmtProfessionnel->bindParam(':adresse_id', $adresseId);

        // Exécuter la requête pour le professionnel
        if ($stmtProfessionnel->execute()) {
            // Extraire les valeurs du RIB à partir de l'IBAN
            try {
                $rib = extraireRibDepuisIban($iban);
                $stmtRib = $dbh->prepare("INSERT INTO sae_db._rib (code_banque, code_guichet, numero_compte, cle_rib, compte_id) VALUES (:code_banque, :code_guichet, :numero_compte, :cle_rib, :compte_id)");
                $stmtRib->bindParam(':code_banque', $rib['code_banque']);
                $stmtRib->bindParam(':code_guichet', $rib['code_guichet']);
                $stmtRib->bindParam(':numero_compte', $rib['numero_compte']);
                $stmtRib->bindParam(':cle_rib', $rib['cle_rib']);
                $stmtRib->bindParam(':compte_id', $compte_id); // Assurez-vous que compte_id est défini

                if ($stmtRib->execute()) {
                    $message = "Votre compte a bien été créé. Vous allez maintenant être redirigé vers la page de connexion.";
                } else {
                    $message = "Erreur lors de l'insertion dans la table RIB : " . implode(", ", $stmtRib->errorInfo());
                }
            } catch (Exception $e) {
                $message = "Erreur lors de l'extraction des données RIB : " . $e->getMessage();
            }
        } else {
            $message = "Erreur lors de la création du compte professionnel : " . implode(", ", $stmtProfessionnel->errorInfo());
        }
    } else {
        $message = "Erreur lors de l'insertion dans la table Adresse : " . implode(", ", $stmtAdresse->errorInfo());
    }
}

ob_end_flush();
?>

<!-- Affichage du message dans le HTML -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Compte</title>
    <script>
        // Fonction de redirection après un délai
        function redirectToLogin() {
            setTimeout(function() {
                window.location.href = "login-pro.php";
            }, 5000); // 5000 ms = 5 secondes
        }
    </script>
</head>
<body>
    <h1>Création de Compte</h1>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
        <script>redirectToLogin();</script>
    <?php endif; ?>

    <!-- Formulaire de création de compte ici -->
</body>
</html>

<?php } ?>

<script>
// Gestion des icônes pour afficher/masquer le mot de passe
const togglePassword1 = document.getElementById('togglePassword1');
const togglePassword2 = document.getElementById('togglePassword2');
const mdp = document.getElementById('mdp');
const confMdp = document.getElementById('confMdp');

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
    const statut = document.getElementById('statut').value;
    const labelNom = document.getElementById('nom');

    if (statut === 'public') {
        labelNom.textContent = 'Nom de l\'organisation';
    } else {
        labelNom.textContent = 'Dénomination sociale';
    }
}

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

// Fonction pour formater l'IBAN
function formatIBAN(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    const prefix = "FR"; // Préfixe de l'IBAN
    const formattedValue = value.length > 0 ? (prefix + value).match(/.{1,4}/g)?.join(' ') : prefix; // Formatage de l'IBAN
    input.value = formattedValue;
}
</script>
