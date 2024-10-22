<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../styles/output.css">
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script type="module" src="../dockerBDD/connexion/pro/crea_offre.js"></script>
    <style>
        .offer-form-part-1{
            display: none;
            margin-top: 10px;
        }

        .offer-form-part-2{
            display: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Conteneur principal pour le contenu -->
    <div class="flex justify-center align-baseline pb-8">
        <div class="bg-base200 w-[1024px] flex flex-col items-center justify-center p-8 rounded-xl">
            <!-- Lien de retour avec une icône et un titre -->
            <div class="w-full text-left">
                <a href="accueil-pro.html" class="flex content-center space-x-">
                    <div class="m-4">
                        <i class="fa-solid fa-arrow-left fa-2xl w-4 h-4 mr-2"></i>        
                    </div>
                    <div class="my-2">
                        <h1 class="text-h1"> Création d'offre</h1>
                    </div>
                </a>
            </div>
            <!-- Section de sélection de l'offre -->
            <div class="flex flex-wrap sm:flex-nowrap sm:space-x-[50px] space-y-6 sm:space-y-0 p-6">
                <!-- Carte de l'offre gratuite -->
                <div class="border border-secondary rounded-lg flex-col justify-center w-full text-secondary p-4 has-[:checked]:bg-secondary has-[:checked]:text-white sm:h-full">
                    <input type="radio" name="offer" id="offer1" class="hidden" checked onclick="gratuit()">
                    <label for="offer1" class="divide-y divide-current cursor-pointer flex flex-col justify-between h-full">
                        <div class="h-full divide-y divide-current">
                            <div>
                                <h1 class="text-h1 leading-none mt-1 text-center">Gratuite</h1>
                                <h1 class="text-center font-bold">Pour les associations et les organismes publics</h1>
                            </div>
                            <div>
                                <div class="ml-8">
                                    <ul class="list-disc text-left text-small my-2">
                                        <li>Jusqu’à 10 photos de présentations</li>
                                        <li>Réponse aux avis des membres</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-h1 leading-none mt-1 text-center py-2">0€/mois</h1>
                        </div>
                    </label>
                </div>
                <!-- Carte de l'offre standard -->
                <div class="border border-primary rounded-lg flex-col justify-center w-full text-primary p-4 has-[:checked]:bg-primary has-[:checked]:text-white sm:h-full">
                    <input type="radio" name="offer" id="offer2" class="hidden" checked onclick="standard()">
                    <label for="offer2" class="divide-y divide-current cursor-pointer flex flex-col justify-between h-full">
                        <div class="h-full divide-y divide-current">
                            <div>
                                <h1 class="text-h1 leading-none mt-1 text-center">Standard</h1>
                                <h1 class="text-center font-bold">Pour les entreprises et organismes privés</h1>
                            </div>
                            <div class="h-full">
                                <div class="ml-8">
                                    <ul class="list-disc text-left text-small my-2">
                                        <li>Jusqu’à 10 photos de présentations</li>
                                        <li>Réponse aux avis des membres</li>
                                        <li>Possibilité de souscrire aux options “À la une” et “En relief”</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                            <div>  
                                <h1 class="text-h1 leading-none mt-1 text-center py-2">12€/mois</h1>
                            </div>
                    </label>
                </div>
                <!-- Carte de l'offre premium -->
                <div class="border border-secondary rounded-lg flex-col justify-center w-full text-secondary p-4 has-[:checked]:bg-secondary has-[:checked]:text-white sm:h-full">
                    <input type="radio" name="offer" id="offer3" class="hidden" checked onclick="premium()">
                    <label for="offer3" class="divide-y divide-current cursor-pointer flex flex-col justify-between h-full">
                        <div class="h-full divide-y divide-current">
                            <div>
                                <h1 class="text-h1 leading-none mt-1 text-center">Premium</h1>
                                <h1 class="text-center font-bold">Pour les entreprises et organismes privés</h1>
                            </div>
                            <div class="h-full">
                                <p class="mt-2 text-small">Standard +</p>
                                <div class="ml-8">
                                    <ul class="list-disc text-left text-small">
                                        <li>Mise sur liste noire de 3 commentaires</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div>  
                            <p class="text-h1 leading-none mt-1 text-center py-2">19€/mois</p>
                        </div>
                    </label>
                </div>
            </div>
            <!-- Bouton de soumission -->
            <div class="flex justify-center content-center">
                <input type="submit" class="bg-primary text-white w-[300px] font-medium py-2 px-4 rounded-lg inline-flex items-center border border-transparent focus:scale-[0.97] hover:bg-orange-600 hover:border-orange-600 hover:text-white m-1" onclick="showFormPart1()" value="Valider"/>
            </div>
            <br>
            <br>
            <div class="offer-form-part-1" id="part-1">
                <div class="w-[347px] flex flex-col space-y-2">
                        <!-- Sélection du type d'activité -->
                        <div class="text-center">
                            <label for="activityType" class="block text-lg">Quel type d'activité ?</label>
                            <select id="activityType" name="activityType" class="bg-white text-black py-2 px-4 border border-black rounded-lg w-full">
                                <option value="" disabled selected>Type d'activité</option> <!-- Valeur par défaut désactivée -->
                                <option value="Activité">Activité</option>
                                <option value="Visite">Visite</option>
                                <option value="Spectacle">Spectacle</option>
                                <option value="Parc d'attraction">Parc d'attraction</option>
                                <option value="Restauration">Restauration</option>
                            </select>
                        </div>
                    <!-- Formulaire pour entrer les informations -->
                    <div class="flex justify-center content-left">
                        <form action="../dockerBDD/connexion/pro/crea_offre.php" method="post" class="flex-col w-full space-y-3" enctype="multipart/form-data">
                            <!-- Titre -->
                            <div class="flex justify-between items-center w-full space-x-4">
                                <label for="titre" class="text-nowrap">Titre* :</label>
                                <input type="text" class="border border-secondary rounded-lg p-2 bg-white w-full" id="titre" name="titre" required>
                            </div>
                            <!-- TAG -->
                            <div class="flex justify-between items-center w-full space-x-4">
                                <label for="tag" class="text-nowrap">TAG* :</label>
                                <input type="text" class="border border-secondary rounded-lg p-2 bg-white w-full" required id="tag" name="tag">
                            </div>
                            <!-- Auteur -->
                            <div class="flex justify-between items-center w-full space-x-4">
                                <label for="auteur" class="text-nowrap">Auteur* :</label>
                                <input type="text" class="border border-secondary rounded-lg p-2 bg-white w-full" required id="auteur" name="auteur">
                            </div>
                            <!-- Ville & code postal -->
                            <div class="flex justify-between items-center w-full space-x-2">
                                <label for="ville" class="text-nowrap">Ville* :</label>
                                <input type="text" class="border border-secondary rounded-lg p-2 bg-white w-full" required id="ville" name="ville">
                                <label for="code" class="text-nowrap">Code postal* :</label>
                                <input type="number" step="10" class="border border-secondary rounded-lg p-2 bg-white w-full" required id="code" name="code">
                            </div>
                            <!-- Adresse -->
                            <div class="flex justify-between items-center w-full space-x-2">
                                <label for="adresse" class="text-nowrap">Adresse* :</label>
                                <input type="text" class="border border-secondary rounded-lg p-2 bg-white w-full" required id="adresse" name="adresse">
                            </div>
                            <!-- Gamme de prix -->
                            <div class="flex justify-between items-center w-full space-x-2" id="prix-diff">
                                <label for="gamme" class="text-nowrap">Gamme de prix* :</label>
                                <div>
                                    <input type="radio" id="prix1" name="gamme2prix" value="€" checked />
                                    <label for="prix1">€</label>
                                </div>
                                <div>
                                    <input type="radio" id="prix2" name="gamme2prix" value="€€" />
                                    <label for="prix2">€€</label>
                                </div>
                                <div>
                                    <input type="radio" id="prix3" name="gamme2prix" value="€€€" />
                                    <label for="prix3">€€€</label>
                                </div>
                            </div>
                            
                            <!-- Durée & âge requis -->
                            <div class="flex justify-between items-center w-full space-x-2">
                                <label for="duree" class="text-nowrap">Durée (h)* :</label>
                                <input type="number" pattern="/d+/" min="0" class="border border-secondary rounded-lg p-2 bg-white w-full" required id="duree" name="duree">
                                <label for="age" class="text-nowrap">Âge requis* :</label>
                                <input type="number" pattern="/d+/" min="0" max="125" class="border border-secondary rounded-lg p-2 bg-white w-full" required id="age" name="age">
                                
                            </div>
                            <!-- Prix minimal -->
                            <div class="flex justify-between items-center w-full space-x-2">
                                <label for="prix" class="text-nowrap">Prix minimal* :</label>
                                <input type="number" pattern="/d+/" onchange="" min="0" class="border border-secondary rounded-lg p-2 bg-white w-full" required id="prix" name="prix">
                                <p>€</p>
                            </div>
                            <!-- Résumé -->
                            <div class="flex flex-col justify-between items-center w-full space-x-2"> 
                                <label for="resume" class="text-nowrap w-full">Résumé* :</label>
                                <textarea id="resume" name="resume" class="border border-secondary rounded-lg p-2 bg-white w-full" rows="4" placeholder="Le résumé sera visible sur la carte de l'offre ! " required></textarea>
                            </div>
                            <!-- Description -->
                            <div class="flex flex-col justify-between items-center w-full space-x-2"> 
                                <label for="description" class="text-nowrap w-full">Description* :</label>
                                <textarea id="description" name="description" class="border border-secondary rounded-lg p-2 bg-white w-full" rows="15" placeholder="La description sera visible sur l'offre détaillée !" required></textarea>
                            </div>
                            <!-- Photo principale -->
                            <div class="flex flex-col justify-between w-full space-x-2">                             
                                <div class="h-4"></div>
                                <label for="photo-upload-carte" class="text-nowrap w-full">Photo principale : (carte)* :</label>
                                    <input type="file" name="photo-upload-carte" id="photo-upload-carte" class="text-small text-secondary
                                    file:mr-5 file:py-3 file:px-10
                                    file:rounded-lg
                                    file:text-small file:font-bold  file:text-secondary
                                    file:border file:border-secondary
                                    hover:file:cursor-pointer hover:file:bg-secondary hover:file:text-white
                                " accept=".svg,.png,.jpg,.jpeg" required/>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG, JPG.</p>
                            </div>
                            <div id="message"></div>

                            <!-- Photos détaillée -->
                            <div class="flex flex-col justify-between w-full space-x-2">                             
                                <div class="h-4"></div>
                                <label for="photo-detail" class="text-nowrap w-full">Photos de l'offre détaillée :</label>
                                    <input type="file" name="photo-detail" id="photo-detail" class="text-small text-secondary
                                    file:mr-5 file:py-3 file:px-10
                                    file:rounded-lg
                                    file:text-small file:font-bold  file:text-secondary
                                    file:border file:border-secondary
                                    hover:file:cursor-pointer hover:file:bg-secondary hover:file:text-white
                                "/>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help_detail"> SVG, PNG, JPG.</p>
                            </div>

                            <!-- Bouton soumission -->
                            <input type="button" class="bg-primary text-white font-medium py-2 px-4 rounded-lg inline-flex items-center border border-transparent focus:scale-[0.97] hover:bg-orange-600 hover:border-orange-600 hover:text-white w-full m-1" value="Une dernière étape" onclick="showFormPart2()">
                        </div>
                        <div class="offer-form-part-2" id="part-2">
                            <div class="avecOptions" id="op">
                                <!-- Les options -->
                                <h1 class="text-h1 text-center text-secondary">Les options</h1>
                                <div class="flex justify-center">
                                    <a  href="" class="text-small text-center underline text-secondary"> Voir les CGU</a>
                                </div>
                                <div class="flex flex-row space-x-8 content-center justify-center items-center text-secondary ">
                                    <!-- Option en relief -->
                                    <div>
                                        <input type="radio" id="option-relief" name="option" value="option-relief" checked/>
                                        <label for="option-relief">En Relief</label>
                                    </div>
                                    <!-- À la une -->
                                    <div>
                                        <input type="radio" id="option-a-la-une" name="option" value="option-a-la-une"/>
                                        <label for="option-a-la-une">À la une</label>
                                    </div>
                                    <!-- Sans option -->
                                    <div>
                                        <input type="radio" id="option-rien" name="option" value="option-rien" class="border"/>
                                        <label for="option-rien">Sans option</label>
                                    </div>
                                </div>
                            </div>
                            <!-- Affiche de la carte en fonction de l'option choisie et des informations rentrées au préalable. -->
<div class="card active relative bg-base300 rounded-lg flex h-[400px]">
    <!-- Partie gauche -->
    <div class="gauche relative shrink-0 basis-1/2 overflow-hidden">
        <!-- En tête -->
        <div class="en-tete flex justify-around absolute top-0 w-full">
            <div class="bg-bgBlur/75 backdrop-blur rounded-b-lg w-3/5">
                <!-- Mise à jour du titre en temps réel -->
                <script>
                    document.getElementById('titre').addEventListener('input', function() {
                        document.getElementById('preview-titre').textContent = document.getElementById('titre').value;
                    });
                </script>
                <h3 class="text-center font-bold" id="preview-titre"></h3>
                <div class="flex w-full justify-between px-2">
                    <!-- Mise à jour de l'auteur en temps réel -->
                    <script>
                        document.getElementById('auteur').addEventListener('input', function() {
                            document.getElementById('preview-auteur').textContent = document.getElementById('auteur').value;
                        });
                    </script>
                    <p class="text-small" id="preview-auteur"></p>
                    <p class="text-small" id="preview-activite"></p>
                    <!-- Mise à jour de l'activité en fonction de la sélection -->
                    <script>
                        // Fonction pour mettre à jour la sélection d'activité
                        function updateActivite() {
                            // Récupérer la valeur sélectionnée dans le sélecteur
                            const selectedActivite = document.getElementById('activityType').value;
                            // Mettre à jour le texte dans la prévisualisation
                            document.getElementById('preview-activite').textContent = selectedActivite;
                        }

                        // Ajouter un EventListener pour détecter les changements dans le sélecteur
                        document.getElementById('activityType').addEventListener('change', updateActivite);

                        // Appeler la fonction une première fois pour l'initialisation avec la valeur par défaut
                        updateActivite();
                    </script>
                </div>
            </div>
        </div>
        <!-- Image de fond -->
        <script>
            document.getElementById('photo-detail').addEventListener('change', function(event) {
                const file = event.target.files[0]; // Récupérer le fichier sélectionné
                const previewImage = document.getElementById('preview-image'); // Élément d'image à mettre à jour

                if (file) {
                    const reader = new FileReader(); // Créer un nouvel objet FileReader
                    reader.onload = function(e) {
                        previewImage.src = e.target.result; // Mettre à jour la source de l'image avec le fichier
                    };
                    reader.readAsDataURL(file); // Lire le fichier comme une URL de données
                } else {
                    previewImage.src = '#'; // Image par défaut ou vide si aucun fichier
                }
            });
        </script>
        <img class="rounded-l-lg w-full h-full object-cover object-center" src="../public/images/image-test.jpg" alt="Image promotionnelle de l'offre" id="preview-image">
    </div>
    <!-- Partie droite (infos principales) -->
    <div class="infos flex flex-col items-center self-stretch px-5 py-3 gap-3 justify-between">
        <!-- Description -->
        <div class="description py-2 flex flex-col gap-2 h-full">
            <div class="p-2 rounded-lg bg-secondary self-center">
                <!-- Mise à jour du tag en temps réel -->
                <script>
                    document.getElementById('tag').addEventListener('input', function() {
                        document.getElementById('preview-tag').textContent = document.getElementById('tag').value;
                    });
                </script>
                <p class="text-white text-center font-bold" id="preview-tag"></p>
            </div>
            <!-- Mise à jour du résumé en temps réel -->
            <script>
                document.getElementById('resume').addEventListener('input', function() {
                    document.getElementById('preview-resume').textContent = document.getElementById('resume').value;
                });
            </script>
            <p class="line-clamp-6" id="preview-resume"></p>
        </div>
        <!-- A droite, en bas -->
        <div class="self-stretch flex flex-col shrink-0 gap-2">
            <hr class="border-black w-full">
            <div class="flex justify-around self-stretch">
                <!-- Localisation -->
                <div class="localisation flex flex-col gap-2 flex-shrink-0 justify-center items-center">
                    <i class="fa-solid fa-location-dot"></i>
                    <!-- Mise à jour de la ville en temps réel -->
                    <script>
                        document.getElementById('ville').addEventListener('input', function() {
                            document.getElementById('preview-ville').textContent = document.getElementById('ville').value;
                        });
                    </script>
                    <p class="text-small" id="preview-ville"></p>
                    <!-- Mise à jour du code postal en temps réel -->
                    <script>
                        document.getElementById('code').addEventListener('input', function() {
                            document.getElementById('preview-code').textContent = document.getElementById('code').value;
                        });
                    </script>
                    <p class="text-small" id="preview-code"></p>
                </div>
                <!-- Notation et Prix -->
                <div class="localisation flex flex-col flex-shrink-0 gap-2 justify-center items-center">
                    <p class="text-small" id="preview-prix-diff">€</p> <!-- Valeur par défaut -->
                </div>
                <!-- Mise à jour de la gamme de prix -->
                <script>
                    // Fonction pour mettre à jour la gamme de prix
                    function updatePrixDiff() {
                        // Récupérer la valeur du bouton radio sélectionné
                        const selectedPrix = document.querySelector('input[name="gamme2prix"]:checked').value;
                        // Mettre à jour le texte dans la prévisualisation
                        document.getElementById('preview-prix-diff').textContent = selectedPrix;
                    }

                    // Ajouter un EventListener pour détecter les changements dans le groupe de boutons radio
                    document.querySelectorAll('input[name="gamme2prix"]').forEach((radio) => {
                        radio.addEventListener('change', updatePrixDiff);
                    });

                    // Appeler la fonction une première fois pour l'initialisation avec la valeur par défaut
                    updatePrixDiff();
                </script>
            </div>
        </div>
    </div>
</div>
<!-- Créer l'offre -->
<input type="submit" value="Créer l'offre" class="bg-secondary text-white font-medium py-2 px-4 rounded-lg inline-flex items-center border border-transparent hover:border hover:bg-green-900 hover:border-green-900 focus:scale-[0.97] w-full m-1">
</form>
</div>
</div>
</div>
</div>

<script src="ajout.js"></script>
</body>
</html>