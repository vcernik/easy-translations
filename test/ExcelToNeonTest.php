<?php
use Tester\Assert;
use Nette\Neon\Neon;

require __DIR__ . '/../vendor/autoload.php';
Tester\Environment::setup();


use vcernik\EasyTranslations\EasyTranslations;


/**
 * without empty strings
 */

EasyTranslations::ExcelToNeon('testETN.xlsx','output');

$pattern=file_get_contents('testETN1pattern/main.cs.neon');
$output=file_get_contents('output/main.cs.neon');
Assert::same($pattern, $output);

$pattern=file_get_contents('testETN1pattern/main.en.neon');
$output=file_get_contents('output/main.en.neon');
Assert::same($pattern, $output);

$pattern=file_get_contents('testETN1pattern/messages.cs.neon');
$output=file_get_contents('output/messages.cs.neon');
Assert::same($pattern, $output);

$pattern=file_get_contents('testETN1pattern/messages.en.neon');
$output=file_get_contents('output/messages.en.neon');
Assert::same($pattern, $output);


/**
 * empty strings
 */

EasyTranslations::ExcelToNeon('testETN.xlsx','output', true);

$pattern=file_get_contents('testETN2pattern/messages.en.neon');
$output=file_get_contents('output/messages.en.neon');
Assert::same($pattern, $output);