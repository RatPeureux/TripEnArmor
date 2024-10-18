<?php

include('../connect_params.php');

if (!isset($_POST['email'])) {
    // Formulaire de création de compte (1/2)
    ?>
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
                <form class="bg-base200 w-full p-5 rounded-lg border-2 border-primary" action="crea_compte_membre.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <p class="pb-3">Je créé un compte Membre</p>

                    <div class="flex flex-nowrap space-x-3 mb-1.5">
                        <div class="w-full">
                            <label class="text-small" for="prenom">Prénom*</label>
                            <input class="p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="prenom" name="prenom" pattern="^[A-Z][a-zA-Zéèêëàâôûç\-']+(?:\s[A-Z][a-zA-Zéèêëàâôûç\-']+)*$" title="Saisir mon prénom" maxlength="50" required>
                        </div>
                        <div class="w-full">
                            <label class="text-small" for="nom">Nom*</label>
                            <input class="p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="nom" name="nom" pattern="^[A-Z][a-zA-Zéèêëàâôûç\-']+(?:\s[A-Z][a-zA-Zéèêëàâôûç\-']+)*$" title="Saisir mon nom" maxlength="50" required>
                        </div>
                    </div>
                    
                    <label class="text-small" for="email">Adresse email*</label>
                    <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="email" id="email" name="email" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Saisir une adresse email" maxlength="255" required>
                
                    <label class="text-small" for="mdp">Mot de passe</label>
                    <div class="relative w-full">
                        <input class="p-2 pr-12 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="password" id="mdp" name="mdp" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?&quot;:{}|&lt;&gt;])[A-Za-z\d!@#$%^&*(),.?&quot;:{}|&gt;&lt;]{8,}" title="Saisir un mot de passe" minlength="8" autocomplete="current-password" required>
                        <i class="fa-regular fa-eye fa-lg absolute top-6 right-4 cursor-pointer" id="togglePassword"></i>
                    </div>

                    <label class="text-small" for="confMdp">Confirmer le mot de passe*</label>
                    <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="password" id="confMdp" name="confMdp" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?&quot;:{}|&lt;&gt;])[A-Za-z\d!@#$%^&*(),.?&quot;:{}|&gt;&lt;]{8,}" title="Confirmer mon mot de passe" minlength="8" autocomplete="new-password" required>

                    <span id="error-message" class="error text-rouge-logo text-small"></span>

                    <input type="submit" value="Continuer" class="cursor-pointer w-full h-12 my-1.5 bg-primary text-white font-bold rounded-lg inline-flex items-center justify-center border border-transparent focus:scale-[0.97] hover:bg-orange-600 hover:border-orange-600 hover:text-white">
                    <a href="login-member.html" class="w-full h-12 p-1 bg-transparent text-primary font-bold rounded-lg inline-flex items-center justify-center border border-primary hover:text-white hover:bg-orange-600 hover:border-orange-600 focus:scale-[0.97]"> 
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

    <?php 
} else {
    // Partie pour le second formulaire
    $prenom = ucfirst(strtolower($_POST['prenom']));
    $nom = strtoupper($_POST['nom']);
    $email = strtolower($_POST['email']);
    $mdp = $_POST['mdp']; // Récupérer le mot de passe
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
            <form class="mb-4 bg-base200 w-full p-5 rounded-lg border-2 border-primary" action="" method="post" enctype="multipart/form-data">
                <p class="pb-3">Dites-nous en plus !</p>

                <div class="flex flex-nowrap space-x-3 mb-1.5">
                    <div class="w-full">
                        <label class="text-small" for="prenom">Prénom</label>
                        <input class="p-2 text-gris bg-base100 w-full h-12 rounded-lg" type="text" id="prenom" name="prenom" title="Mon prénom" value="<?php echo htmlspecialchars($prenom); ?>" readonly>
                    </div>
                    <div class="w-full">
                        <label class="text-small" for="nom">Nom</label>
                        <input class="p-2 text-gris bg-base100 w-full h-12 rounded-lg" type="text" id="nom" name="nom" title="Mon nom" value="<?php echo htmlspecialchars($nom); ?>" readonly>
                    </div>
                </div>

                <label class="text-small" for="email">Adresse email*</label>
                <input class="p-2 text-gris bg-base100 w-full h-12 mb-1.5 rounded-lg" type="email" id="email" name="email" title="Mon adresse email" value="<?php echo htmlspecialchars($email); ?>" readonly>
                
                <label class="text-small" for="pseudo">Pseudo*</label>
                <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="pseudo" name="pseudo" title="Choisir un pseudo" required>

                <label class="text-small" for="adresse">Adresse*</label>
                <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="adresse" name="adresse" title="Saisir mon adresse" required>

                <div class="flex flex-nowrap space-x-3 mb-1.5">
                    <div class="w-full">
                        <label class="text-small" for="code">Code Postal*</label>
                        <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="code" name="code" pattern="^\d{5}$" title="Saisir un code postal valide" required>
                    </div>
                    <div class="w-full">
                        <label class="text-small" for="ville">Ville*</label>
                        <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="ville" name="ville" title="Saisir ma ville" required>
                    </div>
                </div>

                <label class="text-small" for="tel">Téléphone*</label>
                <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="tel" id="tel" name="tel" pattern="^(0|\+33)[1-9]\d{8}$" title="Saisir un numéro de téléphone valide" required>

                <input type="hidden" name="mdp" value="<?php echo htmlspecialchars($mdp); ?>"> <!-- Champ caché pour le mot de passe -->

                <div class="mb-1.5 flex items-start">
                    <input class="mt-0.5 mr-1.5" type="checkbox" id="termes" name="termes" title="" required>
                    <label class="text-small">J’accepte les <u>conditions d'utilisation</u> et vous confirmez que vous avez lu notre <u>Politique de confidentialité et d'utilisation des cookies</u>.</label>
                </div>

                <input type="submit" value="Créer mon compte" class="cursor-pointer w-full h-12 my-1.5 bg-primary text-white font-bold rounded-lg inline-flex items-center justify-center border border-transparent focus:scale-[0.97] hover:bg-orange-600 hover:border-orange-600 hover:text-white">
            </form>
        </div>
    </body>
    </html>

    <?php
} 

    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Gère les erreurs de PDO

// Partie pour traiter la soumission du second formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pseudo'], $_POST['adresse'], $_POST['code'], $_POST['ville'], $_POST['tel'], $_POST['termes'], $_POST['mdp'])) {
    // Assurer que tous les champs obligatoires sont remplis
    $pseudo = $_POST['pseudo'];
    $adresse = $_POST['adresse'];
    $code = $_POST['code'];
    $ville = $_POST['ville'];
    $tel = $_POST['tel'];
    $mdp = $_POST['mdp']; // Récupérer le mot de passe du champ caché

    // Hachage du mot de passe
    if (!empty($mdp)) { // Vérifier si $mdp n'est pas vide
        $mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);

        // Insérer dans la base de données
        $stmt = $dbh->prepare("INSERT INTO sae._membre (nom, prenom, email, motdepasse, pseudo) VALUES (:nom, :prenom, :email, :mdp, :pseudo)");
        
        // Lier les paramètres
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mdp', $mdp_hache);
        $stmt->bindParam(':pseudo', $pseudo);

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
