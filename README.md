# CoverageBadge

![](./badges/php.svg)

CoverageBadge is a library for creating an SVG coverage badge from a Clover XML file.

## Installation

Composer

`composer require codebtech/coveragebadge --dev`

## Features
- Generate coverage badge from PHPUnit Clover XML file
- Generate coverage badge based on multiple Clover XML files, and it merges the coverage percentage automatically
- Can accept coverage name and the badge will be generated with the coverage name


## Usage

1. Generate [XML Code Coverage](https://docs.phpunit.de/en/11.1/code-coverage.html) and generate [Clover](https://docs.phpunit.de/en/11.1/configuration.html#the-report-element) XML files.
2. Run `vendor/bin/coverage-badge /path/to/clover.xml /path/to/badge/destination.svg test-name`
3. To merge multiple clover files provide the input XML files `comma` separated run `vendor/bin/coverage-badge /path/to/clover.xml,/path/to/clover2.xml /path/to/badge/destination.svg test-name`
