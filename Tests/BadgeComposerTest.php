<?php

namespace CodeB\CoverageBadge\Tests;

use Exception;
use ReflectionException;
use PHPUnit\Framework\TestCase;
use CodeB\CoverageBadge\BadgeComposer;

class BadgeComposerTest extends TestCase
{
    private BadgeComposer $badgeComposer;

    private string $inputFile = __DIR__ . "/test-input1.xml";
    private string $inputFile2 = __DIR__ . "/test-input2.xml";
    private string $outputFile = "output.svg";
    private string $coverageName = "unit";

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->badgeComposer = new BadgeComposer($this->inputFile, $this->outputFile, $this->coverageName);
    }

    /**
     * @throws ReflectionException
     */
    public function validateFiles(array $files, string $output)
    {
        $method = (new \ReflectionClass($this->badgeComposer))->getMethod('validateFiles');

        return $method->invoke($this->badgeComposer, $files, $output);
    }

    /**
     * Check if an exception is thrown when trying to validate files that do not exist.
     * @throws ReflectionException
     */
    public function testErrorIsThrownWhenInputFileDoesNotExist(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('input file does not exist: file_does_not_exist.xml');

        $this->validateFiles(
            ["file_does_not_exist.xml"],
            "output.svg"
        );
    }

    /**
     * Check if an exception is thrown when the output file is not specified.
     * @throws ReflectionException
     */
    public function testErrorIsThrownWhenOutputFileDoesNotExist(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('output file name is mandatory');

        $this->validateFiles(
            [__DIR__ . "/test-input1.xml"],
            ""  // Empty name to simulate missing output file
        );
    }

    /**
     * @throws ReflectionException
     */
    public function processFile(string $inputFile)
    {
        $method = (new \ReflectionClass($this->badgeComposer))->getMethod('processFile');

        return $method->invoke($this->badgeComposer, $inputFile);
    }

    /**
     * @throws ReflectionException
     */
    public function testProcessTheCloverFileAndCalculateTheCoverage(): void
    {
        $this->processFile($this->inputFile);

        $this->assertEquals(51, $this->badgeComposer->getTotalCoverage());
    }

    /**
     * @throws ReflectionException
     */
    public function testProcessMultipleCloverFilesAndCalculateTheCoverage(): void
    {
        $this->processFile($this->inputFile);
        $this->processFile($this->inputFile2);

        $this->assertEquals(94, $this->badgeComposer->getTotalCoverage());
    }
}
