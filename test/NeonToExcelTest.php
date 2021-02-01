<?php
use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';
Tester\Environment::setup();


use vcernik\EasyTranslations\EasyTranslations;

/**
 * Simple files, 2 languages
 */
EasyTranslations::NeonToExcel('test1','output/temptest1.csv');
$pattern=file_get_contents('test1pattern.csv');
$output=file_get_contents('output/temptest1.csv');
Assert::same($pattern, $output);

/**
 * Something missing in one language
 */
EasyTranslations::NeonToExcel('test2','output/temptest2.csv');
$pattern=file_get_contents('test2pattern.csv');
$output=file_get_contents('output/temptest2.csv');
Assert::same($pattern, $output);