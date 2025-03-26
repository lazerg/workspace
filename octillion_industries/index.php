<?php

require 'vendor/autoload.php';
include "ArrangeIndustries.php";

$arrangeIndustries = (new ArrangeIndustries())->handle();

// Convert the array to a properly formatted string
function arrayToPhpSyntax($array, $indent = 1) {
    $output = "[\n";
    $space = str_repeat("    ", $indent);

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $value = arrayToPhpSyntax($value, $indent + 1);
        } else {
            $value = var_export($value, true);
        }

        if (is_int($key)) {
            $output .= "{$space}{$value},\n";
        } else {
            $output .= "{$space}'{$key}' => {$value},\n";
        }
    }

    $output .= str_repeat("    ", $indent - 1) . "]";
    return $output;
}

// Format and save the array
$fileContent = "<?php\n\nreturn " . arrayToPhpSyntax($arrangeIndustries) . ";\n";
file_put_contents('config.php', $fileContent);
