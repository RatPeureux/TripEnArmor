<?php
include('../connect_params.php');
session_start();

$error = "";

try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nomentreprise = trim($_POST['nomentreprise']);
        $numsiren = (int) trim($_POST['numsiren']);

        error_log("Input: nomentreprise=$nomentreprise, numsiren=$numsiren");

        $stmt = $dbh->prepare("SELECT * FROM sae._professionnel WHERE nomentreprise = :nomentreprise");
        $stmt->bindParam(':nomentreprise', $nomentreprise);
        $stmt->execute();

        if ($stmt->errorInfo()[0] !== '00000') {
            error_log("SQL Error: " . print_r($stmt->errorInfo(), true));
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log(print_r($user, true));
        
        if ($user && $user['numsiren'] === $numsiren) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['token'] = bin2hex(random_bytes(32));
            header('Location: connected_pro.php?token=' . $_SESSION['token']);
            exit();
        } else {
            $error = "Nom d'entreprise ou numéro SIREN incorrect.";
        }
    }
} catch (PDOException $e) {
    echo "Erreur !: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Connexion Professionnelle</title>
</head>
<body>

<?php if (!empty($error)): ?>
    <div style="color: red;"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form action="" method="post">
    <label for="nomentreprise">Nom d'entreprise :</label>
    <input type="text" name="nomentreprise" id="nomentreprise" required>

    <br>

    <label for="numsiren">Numéro SIREN :</label>
    <input type="text" name="numsiren" id="numsiren" required>

    <br>

    <input type="submit" value="Se connecter">
</form>

</body>
</html>
