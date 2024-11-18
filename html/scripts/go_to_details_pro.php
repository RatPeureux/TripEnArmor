<?php
session_start();

if (isset($_GET['offre_id'])) {
    $_SESSION['offre_id'] = $_GET['offre_id'];
}

header('Location: /pro/offre?id=' . $_GET['offre_id']);
exit();
