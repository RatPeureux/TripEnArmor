<?php
    session_start();

    if (isset($_GET['id'])) {
        $_SESSION['id'] = $_GET['id']; // Set the session variable
    }

    // Optionally, redirect back to the original page or display a message
    header("Location: /pages/details.php"); // Change this to your original page
    exit();
?>
