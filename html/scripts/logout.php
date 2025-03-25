<?php
session_start();

unset($_SESSION['id_membre']);
unset($_SESSION['id_pro']);

$_SESSION['message_pour_notification'] = 'Vous avez été déconnecté(e)';
header('location: /');
