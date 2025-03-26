<?php
session_start();

if (isset($_SESSION['message_pour_notification']) && $_SESSION['message_pour_notification']) {
    ?>
    <script>
        window.onload = () => {
            setTimeout(() => {
                displayNotification("<?php echo $_SESSION['message_pour_notification']; ?>");
            }, 500);
        }
    </script>

    <?php
    unset($_SESSION['message_pour_notification']);
}
?>