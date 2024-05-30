<?php

$inputFiles  = explode(',', $argv[1]);
$outputFile = $argv[2];
$coverageName = isset($argv[3]) ? $argv[3] : 'coverage';

$totalCoverage = 0;
$totalElements = 0;
$checkedElements = 0;

foreach ($inputFiles as $inputFile) {
    if (!file_exists($inputFile)) {
        throw new InvalidArgumentException('Invalid input file provided: ' . $inputFile);
    }

    try {
        $xml = new SimpleXMLElement(file_get_contents($inputFile));

        $metrics = $xml->xpath('//metrics');

        foreach ($metrics as $metric) {
            $totalElements   += (int) $metric['elements'];
            $checkedElements += (int) $metric['coveredelements'];
        }

        $coverage = round(($totalElements === 0) ? 0 : ($checkedElements / $totalElements) * 100);
        $totalCoverage += $coverage;
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

$totalCoverage = $totalCoverage / count($inputFiles); // Average coverage across all files

$template = file_get_contents(__DIR__ . '/templates/badge.svg');

$template = str_replace('{{ total }}', $totalCoverage, $template);

$template = str_replace('{{ coverage }}', $coverageName, $template);

$color = '#a4a61d';      // Default Gray
if ($totalCoverage < 40) {
    $color = '#e05d44';  // Red
} elseif($totalCoverage < 60) {
    $color = '#fe7d37';  // Orange
} elseif($totalCoverage < 75) {
    $color = '#dfb317';  // Yellow
} elseif($totalCoverage < 90) {
    $color = '#a4a61d';  // Yellow-Green
} elseif($totalCoverage < 95) {
    $color = '#97CA00';  // Green
} elseif ($totalCoverage <= 100) {
    $color = '#4c1';     // Bright Green
}

$template = str_replace('{{ total }}', $totalCoverage, $template);
$template = str_replace('{{ color }}', $color, $template);

file_put_contents($outputFile, $template);