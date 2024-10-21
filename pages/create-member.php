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

    <!-- Message d'erreur/success -->
    <?php if (!empty($_GET['message'])): ?>
        <div class="alert bg-yellow-200 text-yellow-800 p-3 rounded-lg mb-4">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>

    <!-- Icône pour revenir à la page précédente -->
    <i onclick="history.back()" class="fa-solid fa-arrow-left fa-2xl cursor-pointer"></i>

    <div class="h-full flex flex-col items-center justify-center">
        <div class="relative w-full max-w-96 h-fit flex flex-col items-center justify-center sm:w-96 m-auto">
            <!-- Logo de l'application -->
            <img class="absolute -top-24" src="../public/images/logo.svg" alt="moine" width="108">

            <form class="bg-base200 w-full p-5 rounded-lg border-2 border-primary" action="create-member.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <p class="pb-3">Je créé un compte Membre</p>

                <!-- Champs pour le prénom et le nom -->
                <div class="flex flex-nowrap space-x-3 mb-1.5">
                    <div class="w-full">
                        <label class="text-small" for="prenom">Prénom*</label>
                        <input class="p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="prenom" name="prenom" pattern="^[a-zA-Zéèêëàâôûç\-']+(?:\s[A-Z][a-zA-Zéèêëàâôûç\-']+)*$" title="Saisir mon prénom" maxlength="50" required>
                    </div>
                    <div class="w-full">
                        <label class="text-small" for="nom">Nom*</label>
                        <input class="p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="nom" name="nom" pattern="^[a-zA-Zéèêëàâôûç\-']+(?:\s[A-Z][a-zA-Zéèêëàâôûç\-']+)*$" title="Saisir mon nom" maxlength="50" required>
                    </div>
                </div>
                
                <!-- Champ pour l'adresse mail -->
                <label class="text-small" for="mail">Adresse mail*</label>
                <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="email" id="mail" name="mail" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Saisir une adresse mail" maxlength="255" required>
            
                <!-- Champ pour le mot de passe -->
                <label class="text-small" for="mdp">Mot de passe</label>
                <div class="relative w-full">
                    <input class="p-2 pr-12 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="password" id="mdp" name="mdp" 
                        pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?&quot;:{}|&lt;&gt;])[A-Za-z\d!@#$%^&*(),.?&quot;:{}|&gt;&lt;]{8,}" 
                        title="Saisir un mot de passe" minlength="8" autocomplete="new-password" required>
                    <i class="fa-regular fa-eye fa-lg absolute top-6 right-4 cursor-pointer" id="togglePassword1"></i>
                </div>

                <!-- Champ pour confirmer le mot de passe -->
                <label class="text-small" for="confMdp">Confirmer le mot de passe</label>
                <div class="relative w-full">
                    <input class="p-2 pr-12 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="password" id="confMdp" name="confMdp" 
                        pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?&quot;:{}|&lt;&gt;])[A-Za-z\d!@#$%^&*(),.?&quot;:{}|&gt;&lt;]{8,}" 
                        title="Saisir le même mot de passe" minlength="8" autocomplete="new-password" required>
                    <i class="fa-regular fa-eye fa-lg absolute top-6 right-4 cursor-pointer" id="togglePassword2"></i>
                </div>

                <!-- Messages d'erreurs -->
                <span id="error-message" class="error text-rouge-logo text-small"></span>

                <!-- Bouton pour continuer -->
                <input type="submit" value="Continuer" class="cursor-pointer w-full h-12 my-1.5 bg-primary text-white font-bold rounded-lg inline-flex items-center justify-center border border-transparent focus:scale-[0.97] hover:bg-orange-600 hover:border-orange-600 hover:text-white">
            
                <!-- Lien vers la page de connexion -->
                <a href="login-member.html" class="w-full h-12 p-1 bg-transparent text-primary font-bold rounded-lg inline-flex items-center justify-center border border-primary hover:text-white hover:bg-orange-600 hover:border-orange-600 focus:scale-[0.97]"> 
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
</script>

<?php } elseif (isset($_POST['mail']) && !isset($_POST['tel'])) { 
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
    <link rel="icon" type="image" href="../public/images/favicon.png">
    <link rel="stylesheet" href="../styles/output.css">
    <title>Création de compte 2/2</title>
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>
</head>
<body class="h-screen bg-base100 pt-4 px-4 overflow-x-hidden">
    <!-- Icône pour revenir à la page précédente -->
    <i onclick="history.back()" class="absolute top-7 fa-solid fa-arrow-left fa-2xl cursor-pointer"></i>

    <div class="w-full max-w-96 h-fit flex flex-col items-end sm:w-96 m-auto">
        <!-- Logo de l'application -->
        <img class="text mb-4" src="../public/images/logo.svg" alt="moine" width="57">

        <form class="mb-4 bg-base200 w-full p-5 rounded-lg border-2 border-primary" action="../dockerBDD/connexion/membre/crea_compte_membre.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <p class="pb-3">Dites-nous en plus !</p>

            <!-- Champs pour le prénom et le nom (en lecture seule) -->
            <div class="flex flex-nowrap space-x-3 mb-1.5">
                <div class="w-full">
                    <label class="text-small" for="prenom">Prénom</label>
                    <input class="p-2 text-gris bg-base100 w-full h-12 rounded-lg" type="text" id="prenom" name="prenom" title="Mon prénom" value="<?php echo $prenom;?>" readonly>
                </div>
                <div class="w-full">
                    <label class="text-small" for="nom">Nom</label>
                    <input class="p-2 text-gris bg-base100 w-full h-12 rounded-lg" type="text" id="nom" name="nom" title="Mon nom" value="<?php echo $nom;?>" readonly>
                </div>
            </div>
            
            <!-- Champ pour l'adresse mail (en lecture seule) -->
            <label class="text-small" for="mail">Adresse mail</label>
            <input class="p-2 text-gris bg-base100 w-full h-12 mb-1.5 rounded-lg" type="email" id="mail" name="mail" title="Mon adresse mail" value="<?php echo $mail;?>" readonly>
            
            <!-- Champ pour le pseudonyme -->
            <label class="text-small" for="pseudo">Pseudonyme*</label>
            <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="pseudo" name="pseudo" pattern="^(?:(\w+|\w+[\.\-_]?\w+)+" title="Saisir mon pseudonyme PACT" maxlength="16" required>
            
            <!-- Champs pour l'adresse -->
            <label class="text-small" for="adresse">Adresse postale*</label>
            <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="adresse" name="adresse" pattern="\d{1,5}\s[\w\s.-]+$" title="Saisir mon adresse postale" maxlength="255" required>
            
            <!-- Choix d'acceptation des termes et conditions -->
            <div class="flex flex-nowrap space-x-3 mb-1.5">
                <div class="w-28">
                    <label class="text-small" for="code">Code postal*</label>
                    <input class="text-right p-2 bg-base100 w-28 h-12 rounded-lg" type="text" id="code" name="code" pattern="^(0[1-9]|[1-8]\d|9[0-5]|2A|2B)\d{3}$" title="Saisir mon code postal" minlength="5" maxlength="5" oninput="number(this)" required>
                </div>
                <div class="w-full">
                    <label class="text-small" for="ville">Ville*</label>
                    <input class="p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="ville" name="ville" pattern="^[a-zA-Zéèêëàâôûç\-'\s]+(?:\s[A-Z][a-zA-Zéèêëàâôûç\-']+)*$" title="Saisir ma ville" maxlength="50" required>
                </div>
            </div>

            <!-- Champ pour le numéro de téléphone -->
            <label class="text-small" for="num_tel">Téléphone*</label>
            <div class="w-full">
                <input class="text-center p-2 bg-base100 w-36 h-12 mb-3 rounded-lg" type="tel" id="num_tel" name="num_tel" pattern="^0\d( \d{2}){4}" title="Saisir un numéro de téléphone" minlength="14" maxlength="14" oninput="formatTEL(this)" required>
            </div>

            <div class="mb-1.5 flex items-start">
                <input class="mt-0.5 mr-1.5" type="checkbox" id="termes" name="termes" title="" required>
                <label class="text-small" for="termes">J’accepte les <u>conditions d'utilisation</u> et vous confirmez que vous avez lu notre <u>Politique de confidentialité et d'utilisation des cookies</u>.</label>
            </div>

            <!-- Messages d'erreurs -->
            <span id="error-message" class="error text-rouge-logo text-small"></span>
            
            <!-- Bouton pour créer le compte -->
            <input type="submit" value="Créer mon compte" class="mt-1.5 cursor-pointer w-full h-12 bg-primary text-white font-bold rounded-lg inline-flex items-center justify-center border border-transparent focus:scale-[0.97] hover:bg-orange-600 hover:border-orange-600 hover:text-white">

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

<?php } else { ?>

<?php } ?>
