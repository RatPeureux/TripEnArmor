<?php

include('../connect_params.php');

?>


<?php if (!isset($_POST['email'])) { ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="../../../public/images/favicon.png">
    <link rel="stylesheet" href="../../../styles/output.css">
    <title>Création de compte 1/2</title>
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>

</head>
<body class="h-screen bg-base100 p-4 overflow-hidden">
    <i onclick="history.back()" class="fa-solid fa-arrow-left fa-2xl cursor-pointer"></i>
    <div class="h-full flex flex-col items-center justify-center">
        <div class="relative w-full max-w-96 h-fit flex flex-col items-center justify-center sm:w-96 m-auto">
            <img class="absolute -top-24" src="../../../public/images/logo.svg" alt="moine" width="108">
            <form class="bg-base200 w-full p-5 rounded-lg border-2 border-secondary" action="crea_compte_pro.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <p class="pb-3">Je créé un compte Professionnel</p>

                <label class="text-small" for="denomination">Dénomination sociale*</label>
                <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="denomination" name="denomination" pattern="^?:(\w+|\w+[\.\-_]?\w+)+$" title="Saisir la dénomination sociale de l'entreprise" maxlength="100" required>
                
                <label class="text-small" for="email">Adresse email*</label>
                <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="eemail" id="email" name="email" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Saisir une adresse email" maxlength="255" required>
                
                <label class="text-small" for="mdp">Mot de passe</label>
                <div class="relative w-full">
                    <input class="p-2 pr-12 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="password" id="mdp" name="mdp" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?&quot;:{}|&lt;&gt;])[A-Za-z\d!@#$%^&*(),.?&quot;:{}|&gt;&lt;]{8,}" title="Saisir un mot de passe" minlength="8" autocomplete="current-password" required>
                    <i class="fa-regular fa-eye fa-lg absolute top-6 right-4 cursor-pointer" id="togglePassword"></i>
                </div>

                <label class="text-small" for="confMdp">Confirmer le mot de passe*</label>
                <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="password" id="confMdp" name="confMdp" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?&quot;:{}|&lt;&gt;])[A-Za-z\d!@#$%^&*(),.?&quot;:{}|&gt;&lt;]{8,}" title="Confirmer le mot de passe" minlength="8" autocomplete="new-password" required>

                <span id="error-message" class="error text-rouge-logo text-small"></span>

                <input type="submit" value="Continuer" class="cursor-pointer w-full h-12 my-1.5 bg-secondary text-white font-bold rounded-lg inline-flex items-center justify-center border border-transparent focus:scale-[0.97] hover:bg-green-900 hover:border-green-900 hover:text-white">
                <a href="login-pro.html" class="w-full h-12 p-1 bg-transparent text-secondary font-bold rounded-lg inline-flex items-center justify-center border border-secondary hover:text-white hover:bg-green-900 hover:border-green-900 focus:scale-[0.97]"> 
                    J'ai déjà un compte
                </a>
            </form>
        </div>
    </div>
</body>
</html>

<script>
const togglePassword = document.getElementById('togglePassword');
const mdp = document.getElementById('mdp');
const confMdp = document.getElementById('confMdp');

togglePassword.addEventListener('click', function () {
  const type = mdp.type === 'password' ? 'text' : 'password';
  mdp.type = type;
  confMdp.type = type;
  
  if (this.classList.contains('fa-eye')) {
    this.classList.remove('fa-eye');
    this.classList.add('fa-eye-slash');
  } else {
    this.classList.remove('fa-eye-slash');
    this.classList.add('fa-eye');
  }
});

function validateForm() {
    var mdp = document.getElementById("mdp").value;
    var confMdp = document.getElementById("confMdp").value;
    var errorMessage = document.getElementById("error-message");

    if (mdp !== confMdp) {
        errorMessage.textContent = "Les mots de passe ne correspondent pas.";
        return false;
    }
    
    errorMessage.textContent = "";
    return true;
}
</script>

<?php } else { 
$denomination = $_POST['denomination'];
$email = strtolower($_POST['email']);
$mdp = $_POST['mdp'];
echo "Le mot de passe est : $mdp";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="../../../public/images/favicon.png">
    <link rel="stylesheet" href="../../../styles/output.css">
    <title>Création de compte 2/2</title>

    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>
</head>
<body class="h-screen bg-base100 pt-4 px-4 overflow-x-hidden">
    <i onclick="history.back()" class="absolute top-7 fa-solid fa-arrow-left fa-2xl cursor-pointer"></i>
    <div class="w-full max-w-96 h-fit flex flex-col items-end sm:w-96 m-auto">
        <img class="text mb-4" src="../../../public/images/logo.svg" alt="moine" width="57">
        <form class="mb-4 bg-base200 w-full p-5 rounded-lg border-2 border-secondary" action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <p class="pb-3">Dites-nous en plus !</p>

            <label class="text-small" for="denomination">Dénomination sociale</label>
            <input class="p-2 text-gris bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="denomination" name="denomination" title="Dénomination sociale" value="<?php echo $denomination;?>" readonly>
            
            <label class="text-small" for="email">Adresse email</label>
            <input class="p-2 text-gris bg-base100 w-full h-12 mb-1.5 rounded-lg" type="eemail" id="email" name="email" title="Adresse email" value="<?php echo $email;?>" readonly>

            <label class="text-small" for="statut">Je suis un organisme&nbsp;</label>
            <select class="text-small my-1.5 bg-base100 p-1 rounded-lg" id="statut" name="statut" title="" required>
                <option value="" disabled selected> --- </option>
                <option value="public">public</option>
                <option value="private">privé</option>
            </select>
            <label class="text-small" for="statut">&nbsp;.</label></br>
            
            <label class="text-small" for="adresse">Adresse postale*</label>
            <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="adresse" name="adresse" pattern="\d{1,5}\s[\w\s.-]+$" title="" maxlength="255" required>
            
            <div class="flex flex-nowrap space-x-3 mb-1.5">
                <div class="w-28">
                    <label class="text-small" for="code">Code postal*</label>
                    <input class="text-right p-2 bg-base100 w-28 h-12 rounded-lg" type="text" id="code" name="code" pattern="^(0[1-9]|[1-8]\d|9[0-5]|2A|2B)[0-9]{3}$" title="Saisir un code postal" minlength="5" maxlength="5" required>
                </div>
                <div class="w-full">
                    <label class="text-small" for="ville">Ville*</label>
                    <input class="p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="ville" name="ville" pattern="^[A-Z][a-zA-Zéèêëàâôûç\-'\s]+(?:\s[A-Z][a-zA-Zéèêëàâôûç\-']+)*$" title="Saisir une ville" maxlength="50" required>
                </div>
            </div>

            <label class="text-small" for="tel">Téléphone*</label>
            <div class="w-full">
                <input class="text-center p-2 bg-base100 w-36 h-12 mb-3 rounded-lg" type="tel" id="tel" name="tel" pattern="0[1-9]([-. ]?[0-9]{2}){4}" title="Saisir un numéro de téléphone" minlength="10" maxlength="14" required>
            </div>
            <div class="group">
                <div class="mb-1.5 flex items-start">
                    <input class="mt-0.5 mr-1.5" type="checkbox" name="iban+rib">
                    <label class="text-small">Je souhaite saisir mes informations bancaires dès maitenant !</u></label>
                </div>

                <div class="hidden group-has-[:checked]:block">
                    <label class="text-small" for="iban">IBAN</label>
                    <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="iban" name="iban" pattern="" title="Saisir un IBAN" minlength="" maxlength="">
                
                    <label class="text-small" for="rib">RIB</label>
                    <div class="flex flex-nowrap space-x-3 mb-3">
                        <div class="w-full">
                            <label class="text-small" for="ribBanque">Banque</label><br>
                            <input class="text-center p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="ribBanque" name="ribBanque" pattern="" title="Saisir un code banque" minlength="" maxlength="">
                        </div>
                        <div class="w-full">
                            <label class="text-small" for="rib-cle">Clé</label><br>
                            <input class="text-center p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="ribCle" name="ribCle" pattern="" title="Saisir une clé" minlength="" maxlength="">
                        </div>
                        <div class="w-full">
                            <label class="text-small" for="rib-guichet">Guichet</label><br>
                            <input class="text-center p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="ribGuichet" name="ribGuichet" pattern="" title="Saisir un code guichet" minlength="" maxlength="">
                        </div>
                    </div>
                </div>
            </div>

            <span id="error-message" class="error text-rouge-logo text-small"></span>

            <input type="hidden" name="mdp" value="<?php echo htmlspecialchars($mdp); ?>"> <!-- Champ caché pour le mot de passe -->

            <div class="mb-1.5 flex items-start">
                <input class="mt-0.5 mr-1.5" type="checkbox" id="termes" name="termes" title="" required>
                <label class="text-small">J’accepte les <u>conditions d'utilisation</u> et vous confirmez que vous avez lu notre <u>Politique de confidentialité et d'utilisation des cookies</u>.</label>
            </div>
            
            <input type="submit" value="Créer mon compte" class="cursor-pointer w-full mt-1.5 h-12 bg-secondary text-white font-bold rounded-lg inline-flex items-center justify-center border border-transparent focus:scale-[0.97] hover:bg-green-900 hover:border-green-900 hover:text-white">
        </form>
    </div>
</body>
</html>

<script>
function validateForm() {
    var checkbox = document.getElementById("termes");
    var errorMessage = document.getElementById("error-message");

    if (!checkbox.checked) {
        errorMessage.textContent = "* Champs obligatoires";
        return false;
    }

    errorMessage.textContent = "";
    return true;
    }
</script>

<?php } 

$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Gère les erreurs de PDO

// Partie pour traiter la soumission du second formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adresse'], $_POST['code'], $_POST['ville'], $_POST['tel'], $_POST['termes'], $_POST['mdp'])) {
// Assurer que tous les champs obligatoires sont remplis
$adresse = $_POST['adresse'];
$code = $_POST['code'];
$ville = $_POST['ville'];
$tel = $_POST['tel'];
$mdp = $_POST['mdp']; // Récupérer le mot de passe du champ caché

// Hachage du mot de passe
if (!empty($mdp)) { // Vérifier si $mdp n'est pas vide
    $mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);

    // Insérer dans la base de données
    $stmt = $dbh->prepare("INSERT INTO sae._organisation (nom, prenom, email, motdepasse, denomination) VALUES ('fzuheg', 'salam', :email, :mdp, :denomination)");
    
    // Lier les paramètres
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mdp', $mdp_hache);
    $stmt->bindParam(':denomination', $denomination);

    // Exécuter la requête
    if ($stmt->execute()) {
        echo "Compte créé avec succès!";
    } else {
        echo "Erreur lors de la création du compte : " . implode(", ", $stmt->errorInfo());
    }
} else {
    echo "Mot de passe manquant.";
}
}
?>