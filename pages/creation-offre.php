<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/styles/output.css">
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&amp;key=<?php echo getenv('GOOGLE_MAPS_API_KEY'); ?>&language=fr "></script>
    <script type="text/javascript" src="../scripts/autocomplete.js"></script>
</head>

    <!-- 
    À FAIRE :
    X lier les champs, VILLE, CODE POSTAL, ADRESSE à l'aide de l'API GOOGLE.
    - Faire les champs de recherches avec TAG, qui sera aussi utilisé pour VISITE : LANGUE, RESTAURATION : REPAS SERVIS (Petit-dej, Brunch, Dej, Diner, Boissons)
    - Appliquer les scripts à tous les champs pour s'assurer de leur conformité
    - Faire le PHP
    - Faire du JS  
    -->

<body>
    <!-- Conteneur principal pour le contenu -->
    <div class="flex justify-center align-baseline pb-8">
        <div class="bg-base200 w-[1024px] flex flex-col items-center justify-center p-8 rounded-xl">
            <!-- Lien de retour avec une icône et un titre -->
            <div class="w-full text-left">
                <a href="" class="flex content-center space-x-">
                    <div class="m-4">
                        <i class="fa-solid fa-arrow-left fa-2xl w-4 h-4 mr-2"></i>        
                    </div>
                    <div class="my-2">
                        <h1 class="text-title"> Création d'offre</h1>
                    </div>
                </a>
            </div>
            <!-- Section de sélection de l'offre -->
            <div class="flex flex-wrap sm:flex-nowrap sm:space-x-[50px] space-y-6 sm:space-y-0 p-6">
                <!-- Carte de l'offre gratuite -->
                <div class="border border-secondary rounded-lg flex-col justify-center w-full text-secondary p-4 has-[:checked]:bg-secondary has-[:checked]:text-white sm:h-full">
                    <input type="radio" name="offer" id="offer1" class="hidden" checked>
                    <label for="offer1" class="divide-y divide-current cursor-pointer flex flex-col justify-between h-full">
                        <div class="h-full divide-y divide-current">
                            <div>
                                <h1 class="text-title leading-none mt-1 text-center">Gratuite</h1>
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
                            <h1 class="text-title leading-none mt-1 text-center py-2">0€/mois</h1>
                        </div>
                    </label>
                </div>
                <!-- Carte de l'offre standard -->
                <div class="border border-primary rounded-lg flex-col justify-center w-full text-primary p-4 has-[:checked]:bg-primary has-[:checked]:text-white sm:h-full">
                    <input type="radio" name="offer" id="offer2" class="hidden" checked>
                    <label for="offer2" class="divide-y divide-current cursor-pointer flex flex-col justify-between h-full">
                        <div class="h-full divide-y divide-current">
                            <div>
                                <h1 class="text-title leading-none mt-1 text-center">Standard</h1>
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
                                <h1 class="text-title leading-none mt-1 text-center py-2">12€/mois</h1>
                            </div>
                    </label>
                </div>
                <!-- Carte de l'offre premium -->
                <div class="border border-secondary rounded-lg flex-col justify-center w-full text-secondary p-4 has-[:checked]:bg-secondary has-[:checked]:text-white sm:h-full">
                    <input type="radio" name="offer" id="offer3" class="hidden" checked>
                    <label for="offer3" class="divide-y divide-current cursor-pointer flex flex-col justify-between h-full">
                        <div class="h-full divide-y divide-current">
                            <div>
                                <h1 class="text-title leading-none mt-1 text-center">Premium</h1>
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
                            <p class="text-title leading-none mt-1 text-center py-2">19€/mois</p>
                        </div>
                    </label>
                </div>
            </div>
            <!-- Bouton de soumission -->
            <div class="flex justify-center content-center">
                <input type="submit" class="bg-primary text-white w-[300px] font-medium py-2 px-4 rounded-lg inline-flex items-center border border-transparent focus:scale-[0.97] hover:bg-orange-600 hover:border-orange-600 hover:text-white m-1" value="Valider"/>
            </div>
            <br>
            <br>
            <div class="w-[347px] flex flex-col space-y-10">
                    <!-- Sélection du type d'activité -->
                <div class="text-center">
                    <label for="activityType" class="block text-lg">Quel type d'activité ?</label>
                    <select id="activityType" name="activityType" class="bg-white text-black py-2 px-4 border border-black rounded-lg w-full">
                        <option value="selection">Type d'activité</option>
                        <option value="activite">Activité</option>
                        <option value="visite">Visite</option>
                        <option value="spectacle">Spectacle</option>
                        <option value="parc_attraction">Parc d'attraction</option>
                        <option value="restauration">Restauration</option>
                    </select>
                </div>
                <!-- Formulaire pour entrer les informations -->
                <div class="flex justify-center content-left">
                    <form action="" method="GET" class="flex-col w-full space-y-3">

                        <!-- Titre -->
                        <div class="flex justify-between items-center w-full space-x-4">
                            <label for="titre" class="text-nowrap">Titre* :</label>
                            <input type="text" id="titre" class="border border-secondary rounded-lg p-2 bg-white w-full" required>
                        </div>

                        <!-- TAG -->
                        <div class="flex flex-col w-full">
                            <label for="tag-input" class="block text-nowrap">Tags* :</label>
                            <div class="relative">
                                <input type="text" id="tag-input" class="bg-white text-black py-2 px-4 border border-black rounded-lg w-full" placeholder="Ajouter un tag..." required />
                                <div class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg max-h-32 overflow-y-auto hidden" id="suggestions"></div>
                            </div>
                            <!-- Liste des suggestions -->
                            <ul class="list-none mt-2 bg-white border border-gray-300 rounded-lg hidden" id="suggestion-list"></ul>
                            <!-- Les tags ajoutés apparaîtront ici -->
                            <div class="tag-container flex flex-wrap p-2 rounded-lg mt-2 hidden" id="tag-container"></div>
                        </div>
                        
                        
                        <!-- Auteur -->
                        <div class="flex justify-between items-center w-full space-x-4">
                            <label for="auteur" class="text-nowrap">Auteur* :</label>
                            <input type="text" id="auteur" class="border border-secondary rounded-lg p-2 bg-white w-full" required>
                        </div>

                        <!-- Adresse -->
                        <div class="flex justify-between items-center w-full space-x-2">
                            <label for="user_input_autocomplete_address" class="text-nowrap">Adresse* :</label>
                            <input type="text" id="user_input_autocomplete_address" name="user_input_autocomplete_address" placeholder="Entrez une adresse" class="border border-secondary rounded-lg p-2 bg-white w-full" required>
                        </div>

                        <!-- Ville & code postal -->
                        <div class="flex justify-between items-center w-full space-x-2">
                            <label for="locality" class="text-nowrap">Ville* :</label>
                            <input type="text" id="locality" class="border border-secondary rounded-lg p-2 bg-white w-full" required>

                        </div>
                        <div class="flex justify-between items-center w-full space-x-2">
                            <label for="postal_code" class="text-nowrap">Code postal* :</label>
                            <input type="text" id="postal_code" class="border border-secondary rounded-lg p-2 bg-white w-full" required>
                        </div>



<!-- PARAMÈTRES DÉPENDANT DE LA CATÉGORIE DE L'OFFRE -->
                        <!-- Visite guidée -->
                        <!-- Visite -->            
                        <div class="flex justify-between items-center w-full space-x-2">
                            <label class="inline-flex items-center cursor-pointer space-x-4">
                                <p>Visite guidée* :</p>
                                <input type="checkbox" value="" class="sr-only peer">
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                            
                        <!-- Gamme de prix -->
                        <!-- Restauration -->
                        <div class="flex justify-between items-center w-full space-x-2"> 
                            <label for="gamme" class="text-nowrap">Gamme de prix* :</label>   
                            <div>
                                <input type="radio" id="prix1" name="gamme2prix" value="prix1" checked />
                                <label for="prix1">€</label>
                            </div>
                            <div>
                                <input type="radio" id="prix2" name="gamme2prix" value="prix2" checked />
                                <label for="prix2">€€</label>
                            </div>
                            <div>
                                <input type="radio" id="prix3" name="gamme2prix" value="prix3" checked />
                                <label for="prix3">€€€</label>
                            </div>
                        </div>

                        <!-- Durée (HEURE & MIN) -->
                        <!-- Activité, Visite, Spectacle -->
                        <div class="flex justify-between items-center w-full space-x-1">
                            <label for="duree" class="text-nowrap">Durée* :</label>
                            <input type="number" id="duree" pattern="/d+/" min="0" max="24" class="border border-secondary rounded-lg p-2 bg-white w-full text-right" required>
                            <label for="minute">h </label>
                            <input type="number" id="minute" pattern="/d+/" min="0" max="59" class="border border-secondary rounded-lg p-2 bg-white w-full text-right" required>
                            <p>min</p>
                        </div>

                        <!-- Âge requis -->
                        <!-- Activité, Parc d'attractions -->
                        <div class="flex justify-between items-center w-full space-x-2">
                            <label for="age-min" class="text-nowrap">Âge requis* :</label>
                            <input type="number" id="age-min" pattern="/d+/" min="0" max="125" class="border border-secondary rounded-lg p-2 bg-white w-full text-right" required>
                            <p>an(s)</p>
                        </div>
                        
                        <!-- Prix minimal -->
                        <!-- Activité, Spectacle -->
                        <div class="flex justify-between items-center w-full space-x-2">
                            <label for="prix-min" class="text-nowrap">Prix minimal* :</label>
                            <input type="number" id="prix-min" pattern="/d+/" onchange="" min="0" class="border border-secondary rounded-lg p-2 bg-white w-full text-right" required>
                            <p>€</p>
                        </div>

                        <!-- Capacité d'accueil -->
                        <!-- Spectacle -->
                        <div class="flex justify-between items-center w-full space-x-2">
                            <label for="place" class="text-nowrap">Capacité d'accueil* :</label>
                            <input type="number" id="place" pattern="/d+/" onchange="" min="0" class="border border-secondary rounded-lg p-2 bg-white w-full text-right" required>
                            <p>personnes</p>
                        </div>

                        <!-- Nombre d'attractions -->
                        <!-- Parc d'attractions -->
                        <div class="flex justify-between items-center w-full space-x-2">
                            <label for="parc-numb" class="text-nowrap">Nombre d'attraction* :</label>
                            <input type="number" id="parc-numb" pattern="/d+/" onchange="" min="0" class="border border-secondary rounded-lg p-2 bg-white w-full text-right" required>
                            <p>attractions</p>
                        </div>
                        
                        <!-- Plan du parc d'attraction -->
                        <!-- Parc d'attraction -->
                        <div class="flex flex-col justify-between w-full space-x-2">                             
                            <div class="h-4"></div>
                            <label for="photo-plan" class="text-nowrap w-full">Plan du parc d'attraction* :</label>
                                <input type="file" name="photo-plan" id="photo-plan" class="text-small text-secondary
                                file:mr-5 file:py-3 file:px-10
                                file:rounded-lg
                                file:text-small file:font-bold  file:text-secondary
                                file:border file:border-secondary
                                hover:file:cursor-pointer hover:file:bg-secondary hover:file:text-white" 
                                accept=".svg,.png,.jpg" required />
                            <p class="mt-1 text-sm text-secondary dark:text-secondary" id="file_input_help">SVG, PNG, JPG.</p>
                        </div>

                        <!-- Résumé -->
                        <div class="flex flex-col justify-between items-center w-full space-x-2"> 
                            <label for="resume" class="text-nowrap w-full">Résumé* :</label>
                            <textarea id="resume" name="resume" class="border border-secondary rounded-lg p-2 bg-white w-full" rows="4" placeholder="Le résumé sera visible sur la carte de l'offre ! " required></textarea>
                        </div>

                        <!-- Description -->
                        <div class="flex flex-col justify-between items-center w-full space-x-2"> 
                            <label for="description" class="text-nowrap w-full">Description* :</label>
                            <textarea id="description" name="description" class="border border-secondary rounded-lg p-2 bg-white w-full" rows="15" placeholder="La description sera visible sur l'offre détaillée !" ></textarea>
                        </div>

                        <!-- Accessibilité -->
                        <div class="flex flex-col justify-between items-center w-full space-x-2"> 
                            <label for="accessibilite" class="text-nowrap w-full">Accessibilité* :</label>
                            <textarea id="accessibilite" name="description" class="border border-secondary rounded-lg p-2 bg-white w-full" rows="4" placeholder="L'accessibilité sera visible sur l'offre détaillée !"></textarea>
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
                                hover:file:cursor-pointer hover:file:bg-secondary hover:file:text-white" 
                                accept=".svg,.png,.jpg" required />
                            <p class="mt-1 text-sm text-secondary dark:text-secondary" id="file_input_help2">SVG, PNG, JPG.</p>
                        </div>

                        <!-- Photos détaillée -->
                        <div class="flex flex-col justify-between w-full space-x-2">                             
                            <div class="h-4"></div>
                            <label for="photo-detail" class="text-nowrap w-full">Photos de l'offre détaillée :</label>
                                <input type="file" name="photo-detail" id="photo-detail" class="text-small text-secondary
                                file:mr-5 file:py-3 file:px-10
                                file:rounded-lg
                                file:text-small file:font-bold  file:text-secondary
                                file:border file:border-secondary
                                hover:file:cursor-pointer hover:file:bg-secondary hover:file:text-white"
                                accept=".svg,.png,.jpg"/>
                            <p class="mt-1 text-sm text-secondary dark:text-secondary" id="file_input_help_detail"> SVG, PNG, JPG.</p>
                        </div>

                        <!-- Bouton dernière étape -->
                        <input type="button" class="bg-primary text-white font-medium py-2 px-4 rounded-lg inline-flex items-center border border-transparent focus:scale-[0.97] hover:bg-orange-600 hover:border-orange-600 hover:text-white w-full m-1" value="Une dernière étape">

                        <!-- Les options -->
                        <!-- Titre -->
                        <h1 class="text-title text-center text-secondary">Les options</h1>

                        <!-- CGU -->
                        <div class="flex justify-center">
                            <a  href="" class="text-small text-center underline text-secondary"> Voir les CGU</a>
                        </div>
                        <!-- Radio button -->
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
                        <!-- Affiche de la carte en fonction de l'option choisie et des informations rentrées au préalable. -->
                        <div class="border border-secondary rounded-lg">
                            <!-- mettre ici les 3 cartes et faire un hidden des composants qu'on ne veut pas en fonction de l'option. -->
                            <br><br><br><br><br><br><br><br><br><br><br>
                        </div>

                        <!-- Créer l'offre -->
                        <input type="submit" value="Créer l'offre" class="bg-secondary text-white font-medium py-2 px-4 rounded-lg inline-flex items-center border border-transparent hover:border hover:bg-green-900 hover:border-green-900 focus:scale-[0.97] w-full m-1">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="../scripts/tagManager.js"></script>

</body>
</html>