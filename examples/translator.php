<?php

require_once 'vendor/autoload.php';

use Dotenv\Dotenv;
use Nigo\Translator\LibreTranslator;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');

$dotenv->load();

$translator = LibreTranslator::create('ru');

echo $translator->translate('Hello!') . PHP_EOL;

