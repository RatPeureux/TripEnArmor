<?php
if (isset($mode) && isset($className) && isset($message)) {
    $modes = [
        "erreur" => "text-red-500 border-red-500",
        "succes" => "text-green-500 border-green-500 bg-green-500/20",
        "validation" => "text-currentColor border-currentColor bg-currentColor/20",
        "annuler" => "text-currentColor border-currentColor"
    ];

    if (!in_array($mode, ["erreur", "succes", "validation", "annuler"])) {
        echo "Warning: mode inconnu";
    } else {
        $className = $modes[$mode] . $className;
    }

    ?>
    <div class="<?php echo $className ?>">
        <p>
            <?php echo $message; ?>
        </p>
    </div>
<?php } ?>