<?php
session_start();
unset($_SESSION['id_membre']);
unset($_SESSION['id_pro']);

print_r($_SESSION);

header('location: /');
