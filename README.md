# CoverageBadge

CoverageBadge is a library for creating an SVG coverage badge from a Clover XML file.

## Installation

Composer!


## Usage

1. Generate [XML Code Coverage](https://phpunit.de/manual/current/en/logging.html#logging.codecoverage.xml) using [PHPUnit](https://phpunit.de/manual/current/en/appendixes.configuration.html#appendixes.configuration.logging)
2. Run `vendor/bin/coverage-badge /path/to/clover.xml /path/to/badge/destination.svg type_of_test`
    * e.g. `vendor/bin/php-coverage-badge build/clover.xml report/coverage.svg unit-test`
