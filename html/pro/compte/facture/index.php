<?php
session_start();
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_params.php';

$pro = verifyPro();
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
    <script type="module" src="/scripts/main.js" defer></script>
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <!-- Pour les requêtes ajax -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Facture - Professionnel - PACT</title>
</head>


<body class="min-h-screen flex flex-col">

    <!-- Inclusion du menu -->
    <div id="menu-pro">
        <?php
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/menu-pro.php';
        ?>
    </div>

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/header-pro.php';
    ?>

    <!-- Partie principale de la page -->
    <main class="grow md:w-full mt-0 m-auto max-w-[1280px] p-2">
        <!-- Chemin de navigation -->
        <p class="text-h3 p-4">
            <a href="/pro/compte">Mon compte</a>
            >
            <a href="/pro/compte/facture" class="underline">Facture</a>
        </p>

        <hr class="mb-8">

        <!-- Montants totaux prévisionnels -->
        <h2 class="text-h2">Montants totaux prévisionnels</h2>
        <br>
        <p>...</p>
        <br>

        <!-- Prévisualiser une facture pour une offre -->
        <div class="block">
            <?php
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/facture_controller.php';

            $stmt = $dbh->prepare("SELECT * FROM sae_db._facture");
            $stmt->execute();
            $factures = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $numero = $factures[0]['numero'];
            $designation = $factures[0]['designation'];

            $numero = "2024-FAC-0001";
            $date_emission = "01/12/2024";

            $idPro = $_SESSION['id_pro'];
            $factureController = new FactureController;
            // $facture = $factureController->getInfoFacture($numero);
            
            if (isset($_SESSION['id_pro'])) {
                $stmtOffre = $dbh->prepare("SELECT * FROM sae_db._offre WHERE id_pro = :id_pro");
                $stmtOffre->bindParam(':id_pro', $_SESSION['id_pro'], PDO::PARAM_INT);

                if ($stmtOffre->execute()) {
                    $offresDuPro = $stmtOffre->fetchAll(PDO::FETCH_ASSOC);

                    if (count($offresDuPro) > 0) {

                        // Connexion avec la bdd
                        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
                        ?>

                        <h2 class="text-h2">Simuler la facturation d'une offre</h2>

                        <select name="offre" id="offre" onchange="loadPreview()">
                            <option value="" disabled selected>Choisir une offre</option>
                            <?php
                            foreach ($offresDuPro as $offre) { ?>
                                <option value="<?php echo htmlspecialchars($offre['id_offre']) ?>">
                                    <?php echo htmlspecialchars($offre['titre']) ?></option>
                                <?php
                            }
                            ?>
                        </select>

                        <div id="facture-preview"></div>

                        <script>
                            function loadPreview() {
                                // Get the selected value
                                const id_offre = document.getElementById('offre').value;

                                $.ajax({
                                    url: '/scripts/load_preview.php',
                                    type: 'GET',
                                    data: {
                                        id_offre: id_offre,
                                    },

                                    // Durant l'exécution de la requête
                                    success: function (response) {
                                        const preview_loaded = response;
                                        $('#facture-preview').append(preview_loaded);
                                    },
                                });
                            }
                        </script>

                        <button onclick="generatePDF()" class="mt-5 bg-blue-500 text-white p-2 rounded">
                            Télécharger la facture en PDF
                        </button>
                    </div>

                    <script>
                        document.getElementById('offre').addEventListener('change', function () {
                            if (this.value) {
                                document.getElementById('facture-details').style.display = 'block';
                            }
                        });

                        async function generatePDF() {
                            const { jsPDF } = window.jspdf;
                            const pdf = new jsPDF();
                            const element = document.querySelector('#facture-details');
                            const canvas = await html2canvas(element);
                            const imgData = canvas.toDataURL('image/png');
                            pdf.addImage(imgData, 'PNG', 10, 10, 190, canvas.height * 190 / canvas.width);
                            pdf.save('facture.pdf');
                        }
                    </script>

                    <?php

                    } else {
                        echo "<p>Vous n'avez pas d'offres en ligne.</p>";
                    }
                } else {
                    echo "Erreur lors de l'exécution de la requête";
                }
            } else {
                echo "La variable de session id_pro n'est pas définie";
            } ?> </div>
    </main>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/footer-pro.php';
    ?>
    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/footer-pro.php';
    ?>

</body>

</html>
</div>
</main>
</body>

</html>