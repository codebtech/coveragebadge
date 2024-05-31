<?php

namespace CodeB\CoverageBadge;

use Exception;

require_once __DIR__ . '/src/BadgeComposer.php';

$inputFiles = $argv[1];
$outputFile = $argv[2];
$coverageName = $argv[3] ?? 'coverage';

try {
    $badgeComposer = new BadgeComposer($inputFiles, $outputFile, $coverageName);
    $badgeComposer->run();
} catch (Exception $e) {
    echo $e->getMessage();
    exit(1);
}
