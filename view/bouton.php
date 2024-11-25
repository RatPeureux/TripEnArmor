<?php
    $modes = [
        "rouge" => "flex items-center justify-center bg-rouge-logo border border-rouge-logo text-white font-medium rounded-lg hover:bg-rouge-logo/90 focus:scale-[0.97 disabled:bg-gray-300 disabled:border-gray-300 py-2 px-4 m-4",

        "rouge-outline" => "flex items-center justify-center bg-white border border-rouge-logo text-rouge-logo font-medium rounded-lg hover:bg-rouge-logo hover:text-white focus:scale-[0.97 disabled:bg-gray-300 disabled:border-gray-300 py-2 px-4 m-4",

        "primary" => "flex items-center justify-center bg-primary border border-primary text-white font-medium rounded-lg hover:bg-primary/90 focus:scale-[0.97 disabled:bg-gray-300 disabled:border-gray-300 py-2 px-4 m-4",

        "primary-outline" => "flex items-center justify-center bg-white border border-primary text-primary font-medium rounded-lg hover:bg-primary hover:text-white focus:scale-[0.97 disabled:bg-gray-300 disabled:border-gray-300 py-2 px-4 m-4",

        "secondary" => "flex items-center justify-center bg-secondary border border-secondary text-white font-medium rounded-lg hover:bg-secondary/90 focus:scale-[0.97 disabled:bg-gray-300 disabled:border-gray-300 py-2 px-4 m-4",

        "secondary-outline" => "flex items-center justify-center bg-white border border-secondary text-secondary font-medium rounded-lg hover:bg-secondary hover:text-white focus:scale-[0.97 disabled:bg-gray-300 disabled:border-gray-300 py-2 px-4 m-4",    
];
if (isset($mode) && isset($message)) {

    if (!in_array($mode, ["rouge", "rouge-outline","primary", "primary-outline", "secondary", "secondary-outline"])) {
        echo "Warning: mode inconnu";
    } else {
        $modeSelected = $modes[$mode];
    }

?>
    <div class="<?php echo $modeSelected?>">
        <?php if (isset($icone)) { ?>
            <i class="<?php echo $icone?> fill-current w-4 h-5 mr-2"></i>
        <?php
    }
    ?>
        <p>
            <?php echo $message; ?>
        </p>
    </div>
<?php
} 
elseif (isset($message) == false) {
    $modeSelected = $modes[$mode];
}
unset($mode, $message, $icone);
?> 