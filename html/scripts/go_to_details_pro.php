<?php
session_start();

header('Location: /pro/offre?id=' . $_GET['id_offre']);
exit();
