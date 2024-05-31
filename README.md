# CoverageBadge

![](./badges/php.svg)

CoverageBadge is a library for creating SVG coverage badges from Clover XML files.

## Installation

`composer require codebtech/coveragebadge --dev`

## Features
- Produces a code coverage badge utilizing a Clover coverage XML file
- Creates a code coverage badge from several Clover XML files, automatically incorporating the coverage percentages
- Accepts a coverage name as an input to include in the generated badge

## Usage
- Execute the command to generate badge for single Clover XML input 
```
vendor/bin/coverage-badge /path/to/clover.xml /path/to/badge/destination.svg test-name
```
- To blend multiple Clover files, enumerate the XML inputs separated by commas and use the command 
```
vendor/bin/coverage-badge /path/to/clover.xml,/path/to/clover2.xml /path/to/badge/destination.svg test-name
```

## Acknowledgements

This library is inspired by [JASchilz/PHPCoverageBadge](https://github.com/JASchilz/PHPCoverageBadge)