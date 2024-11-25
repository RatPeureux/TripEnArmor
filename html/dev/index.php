<?php

$mode = "erreur";
$className = "font-bold";
$message = "Message d'erreur";

require __DIR__ . "/../../view/bouton.php";
// delete($mode, $className, $message);

$mode = "succes";
$className = "font-bold";
$message = "Message de succès";

require __DIR__ . "/../../view/bouton.php";
unset($mode, $className, $message);
?>