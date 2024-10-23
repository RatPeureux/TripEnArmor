<?php
session_start();
session_destroy();
header('location: ../../pages/login-membre.php');
exit();
?>