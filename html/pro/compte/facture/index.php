<?php
session_start();
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_params.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

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
            $facture = $factureController->getFacture($numero);

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

        <table id='facture-table' class='w-full mt-5 border-collapse border border-gray-300'>
            <thead class='border bg-slate-200'>
                <tr class="bg-slate-200 text-left">
                    <th class='p-2 cursor-pointer font-normal' onclick='sortTable(0)' style='width: 150px;'>N°
                    </th>
                    <th class='p-2 cursor-pointer font-normal' onclick='sortTable(1)'>Nom</th>
                    <th class='p-2 cursor-pointer font-normal' onclick='sortTable(2)' style='width: 160px;'>Date
                        d'émission
                    </th>
                    <th class='p-2 cursor-pointer font-normal' onclick='sortTable(3)' style='width: 160px;'>Date
                        d'échéance</th>
                    <th class='p-2 cursor-pointer font-normal' onclick='sortTable(4)' style='width: 100px;'>
                        Montant</th>
                    <th class='p-2 cursor-pointer font-normal' onclick='sortTable(4)' style='width: 25px;'>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php


                // $factures = [
                //     ['numero' => '2024-FAC-0001', 'nom' => 'Facture 1 - Petite pépite', 'date' => '01/11/2024', 'date_echeance' => '01/02/2025', 'montant' => 100],
                //     ['numero' => '2024-FAC-0002', 'nom' => 'Facture 2  - Petite pépite', 'date' => '01/10/2024', 'date_echeance' => '01/03/2025', 'montant' => 200],
                //     ['numero' => '2024-FAC-0003', 'nom' => 'Facture 3  - Petite pépite', 'date' => '01/09/2024', 'date_echeance' => '01/04/2025', 'montant' => 300],
                //     ['numero' => '2024-FAC-0004', 'nom' => 'Facture 4 - Jet-ski en sous-marin #AD', 'date' => '01/08/2024', 'date_echeance' => '01/05/2025', 'montant' => 400],
                //     ['numero' => '2024-FAC-0005', 'nom' => 'Facture 5 - Dîner très classe', 'date' => '01/12/2024', 'date_echeance' => '01/01/2025', 'montant' => 500],
                // ];
                foreach ($offresDuPro as $offre) {
                    $factures = $factureController->getAllFacturesByIdOffre($offre['id_offre']);

                    foreach ($factures as $facture) {
                        $dateEmission = new DateTime($facture['date_emission']);
                        $dateEcheance = new DateTime($facture['date_echeance']);

                        $nom_table_ligne = "sae_db._ligne_facture_en_ligne";
                        $nom_table_option = "sae_db._ligne_facture_option";

                        $queryLigne = "SELECT prix_total_ttc FROM " . $nom_table_ligne . " WHERE numero_facture = ?";
                        $queryOption = "SELECT prix_total_ttc FROM " . $nom_table_option . " WHERE numero_facture = ?";

                        $statementLigne = $dbh->prepare($queryLigne);
                        $statementLigne->bindParam(1, $facture['numero']);

                        $statementOption = $dbh->prepare($queryOption);
                        $statementOption->bindParam(1, $facture['numero']);

                        if ($statementLigne->execute() && $statementOption->execute()) {
                            $lignes = $statementLigne->fetchAll(PDO::FETCH_ASSOC);
                            $options = $statementOption->fetchAll(PDO::FETCH_ASSOC);
                        } else {
                            echo "ERREUR : Impossible d'obtenir les lignes de la facture";
                        }
                        ?>
                        <tr>
                            <td class='border-b p-2'><?php echo htmlspecialchars($facture['numero']); ?></td>
                            <td class='border-b p-2'><?php echo htmlspecialchars($offre['titre']); ?></td>
                            <td class='border-b p-2'><?php echo $dateEmission->format('d/m/Y'); ?></td>
                            <td class='border-b p-2'><?php echo $dateEcheance->format('d/m/Y'); ?></td>
                            <td class='border-b p-2'>
                                <?php echo array_sum(array_column($lignes, 'prix_total_ttc')) + array_sum(array_column($options, 'prix_total_ttc')) ?> €
                            </td>
                            <td class='border-b p-2'>
                                <a href=""><i class="fa-solid fa-eye hover:text-primary"></i></a>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <script>
            function sortTable(n) {
                const table = document.getElementById("facture-table"); let rows, switching, i, x, y, shouldSwitch,
                    dir, switchcount = 0; switching = true; dir = "asc"; while (switching) {
                        switching = false;
                        rows = table.rows; for (i = 1; i < (rows.length - 1); i++) {
                            shouldSwitch = false;
                            x = rows[i].getElementsByTagName("TD")[n]; y = rows[i + 1].getElementsByTagName("TD")[n]; if
                                (dir == "asc") {
                                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                                    shouldSwitch = true;
                                    break;
                                }
                            } else if (dir == "desc") {
                                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) { shouldSwitch = true; break; }
                            }
                        } if (shouldSwitch) {
                            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                            switching = true; switchcount++;
                        } else {
                            if (switchcount == 0 && dir == "asc") {
                                dir = "desc"; switching = true;
                            }
                        }
                    }
            } </script>
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