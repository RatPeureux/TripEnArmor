<?php
session_start();
session_destroy();
header('Location: ../../../pages/login-member.html');
exit();
?>
