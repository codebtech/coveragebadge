<?php

namespace CodeB\CoverageBadge;

use Exception;

/**
 * Class BadgeComposer
 *
 * This class is responsible for generating a coverage badge based on the input files provided.
 *
 * @package BadgeManager
 */
class BadgeComposer
{
    private array $inputFiles;
    private string $outputFile;
    private string $coverageName;
    private int $totalCoverage = 0;
    private int $totalElements = 0;
    private int $checkedElements = 0;

    /**
     * @throws Exception
     */
    public function __construct(string $inputFiles, string $outputFile, string $coverageName = 'coverage')
    {
        $this->inputFiles = explode(',', $inputFiles);
        $this->outputFile = $outputFile;
        $this->coverageName = $coverageName;

        $this->validateFiles($this->inputFiles, $this->outputFile);
    }

    /**
     * Returns the total coverage percentage.
     *
     * @return int The total coverage percentage.
     */
    public function getTotalCoverage(): int
    {
        return $this->totalCoverage;
    }

    /**
     * Validates the input files and output file.
     *
     * This method checks if the input files exist and if the output file is provided.
     *
     * @param array $inputFiles The array of input files to validate.
     * @param string $outputFile The output file to validate.
     *
     * @return void
     * @throws Exception If any of the input files do not exist or if the output file is not provided.
     */
    private function validateFiles(array $inputFiles, string $outputFile): void
    {
        foreach ($inputFiles as $inputFile) {
            if (!file_exists($inputFile)) {
                throw new \Exception("input file does not exist: " . $inputFile);
            }
        }

        if (empty($outputFile)) {
            throw new \Exception("output file name is mandatory");
        }
    }

    /**
     * Runs the coverage process for each input file.
     *
     * This method iterates over the input files array and processes each file using the `processFile` method.
     * After processing all input files, the coverage is finalized using the `finalizeCoverage` method.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        foreach ($this->inputFiles as $inputFile) {
            $this->processFile($inputFile);
        }
        $this->finalizeCoverage();
    }

    /**
     * Process a file and update the total and checked elements count.
     *
     * @param string $inputFile The path to the XML file to process.
     *
     * @return void
     * @throws Exception When there is an error processing the file.
     *
     */
    private function processFile(string $inputFile): void
    {
        try {
            $xml = new \SimpleXMLElement(file_get_contents($inputFile));
            $metrics = $xml->xpath('//metrics');
            foreach ($metrics as $metric) {
                $this->totalElements   += (int) $metric['elements'];
                $this->checkedElements += (int) $metric['coveredelements'];
            }

            $coverage = round(($this->totalElements === 0) ? 0 : ($this->checkedElements / $this->totalElements) * 100);
            $this->totalCoverage += $coverage;
        } catch (Exception $e) {
            throw new Exception('Error processing file: ' . $inputFile);
        }
    }

    /**
     * Finalize the coverage report by generating a badge with the average coverage across all input files.
     *
     * @return void
     * @throws Exception If there is an error generating the badge.
     */
    private function finalizeCoverage(): void
    {
        $totalCoverage = $this->totalCoverage / count($this->inputFiles); // Average coverage across all files
        $template = file_get_contents(__DIR__ . '/../badge-templates/badge.svg');

        $template = str_replace('{{ total }}', $totalCoverage, $template);

        $template = str_replace('{{ coverage }}', $this->coverageName, $template);

        $color = '#a4a61d';      // Yellow-Green
        if ($totalCoverage < 40) {
            $color = '#e05d44';  // Red
        } elseif ($totalCoverage < 60) {
            $color = '#fe7d37';  // Orange
        } elseif ($totalCoverage < 75) {
            $color = '#dfb317';  // Yellow
        } elseif ($totalCoverage < 95) {
            $color = '#97CA00';  // Green
        } elseif ($totalCoverage <= 100) {
            $color = '#4c1';     // Bright Green
        }

        $template = str_replace('{{ total }}', $totalCoverage, $template);
        $template = str_replace('{{ color }}', $color, $template);

        file_put_contents($this->outputFile, $template);
    }
}
