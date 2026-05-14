<?php
use Tester\Assert;
use Nette\Neon\Neon;

require __DIR__ . '/../vendor/autoload.php';
Tester\Environment::setup();

use vcernik\EasyTranslations\EasyTranslations;

/**
 * custom column names
 */

// Připrav testovací soubor s vlastními názvy sloupců (viz testCustomColumns.csv)

EasyTranslations::ExcelToNeon('testCustomColumns.csv', 'output/custom', columnNames: [
    'domain' => 'modul',
    'id' => 'klic',
]);

$pattern = file_get_contents('testCustomColumnsPattern/main.cs.neon');
$output = file_get_contents('output/custom/main.cs.neon');
Assert::same($pattern, $output);
