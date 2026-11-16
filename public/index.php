<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

header('Content-Type: text/html; charset=utf-8');

readfile(__DIR__ . '/index.html');
