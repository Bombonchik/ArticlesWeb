<?php
// Enable the display of errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/app/core/Application.php';

$app = new Application();