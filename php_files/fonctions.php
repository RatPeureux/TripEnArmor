<?php

if (!function_exists('parse_config_file')) {
    function parse_config_file($var = null)
    {

        $config = [];

        $filePath = dirname($_SERVER['DOCUMENT_ROOT']) . '/config';
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);
            // Clé = valeur
            list($key, $value) = explode('=', $line, 2);

            // Add the key-value pair to the config array
            $config[$key] = $value;
        }

        if ($var) {
            if (isset($config[$var]) && $config[$var]) {
                return $config[$var];
            } else {
                echo "La variable demandée dans le fichier de configuration n'existe pas";
                return -1;
            }
        } else {
            return $config;
        }
    }
}

if (!function_exists('chaineVersMot')) {
    function chaineVersMot($str): string
    {
        return str_replace('_', " d'", ucfirst($str));
    }
}

if (!function_exists('to_nom_note')) {
    function to_nom_note($nom_attribut_note): string
    {
        return str_replace('_', ' ', explode('_', $nom_attribut_note, 2)[1]);
    }
}

if (!function_exists('extraireInfoAdresse')) {
    function extraireInfoAdresse($adresse)
    {
        // Utiliser une expression régulière pour extraire le numéro et l'odonyme
        if (preg_match('/^(\d+)\s+(.*)$/', $adresse, $matches)) {
            return [
                'numero' => $matches[1],
                'odonyme' => $matches[2],
            ];
        }
        // Si l'adresse ne correspond pas au format attendu, retourner des valeurs par défaut
        return [
            'numero' => '',
            'odonyme' => $adresse,
        ];
    }
}
