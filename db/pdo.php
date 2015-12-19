<?php
require_once dirname(__FILE__) . '/../vendor/autoload.php';

// Create (connect to) SQLite database in file
$pdo = new PDO('sqlite:'.dirname(__FILE__) .'/feeds.sqlite');
// Set errormode to exceptions
$pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);

$fpdo = new FluentPDO($pdo);