<?php
session_start();

if (isset($_SESSION['message_pour_notification']) && $_SESSION['message_pour_notification']) {
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                displayNotification("<?php echo $_SESSION['message_pour_notification'] ?>");
            }, 250);
        });
    </script>

    <?php
    unset($_SESSION['message_pour_notification']);
}
