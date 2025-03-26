<?php
phpinfo();
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <title>Test</title>

    <link rel="stylesheet" href="/styles/style.css">
    <script src="/scripts/main.js"></script>

</head>

<body>

    <?php
    require dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';
    use OTPHP\TOTP;

    $totp = TOTP::generate(); // New TOTP
    $totp->setLabel('alice@google.com'); // The label (string)
    
    $goqr_me = $totp->getQrCodeUri(
        'https://api.qrserver.com/v1/create-qr-code/?color=5330FF&bgcolor=70FF7E&data=[DATA]&qzone=2&margin=0&size=300x300&ecc=M',
        '[DATA]'
    );
    echo "<img src='{$goqr_me}'>";
    ?>

</body>

</html>