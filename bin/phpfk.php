<?php

require __DIR__ . '/../vendor/autoload.php';

if (count($argv) < 3) {
    fwrite(STDERR, "Usage: php {$argv[0]} <input_php_file> <output_output_file>\n");
    exit(1);
}

$inputFile = $argv[1];
$outputFile = $argv[2];

if (!file_exists($inputFile)) {
    fwrite(STDERR, "Input file '$inputFile' not found.\n");
    exit(2);
}

$inputCode = file_get_contents($inputFile);
if ($inputCode === false) {
    fwrite(STDERR, "Cannot read input file '$inputFile'.\n");
    exit(3);
}

$inputCode = preg_replace('/^\s*<\?php\s*/', '', $inputCode, 1);

$inputCode = preg_replace('/\?>\s*$/', '', $inputCode);

$obfuscatedCode = obfuscateCode($inputCode);

file_put_contents($outputFile, "<?php set_time_limit(0);error_reporting(0);ini_set('memory_limit','-1');" . $obfuscatedCode . "?>");

echo "Obfuscation done. Output saved to $outputFile.\n";
