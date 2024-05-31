<?php declare(strict_types = 1);

namespace CodeB\CoverageBadge\Tests;

use CodeB\CoverageBadge\BadgeComposer;
use Exception;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use Throwable;
use function file_get_contents;

class BadgeComposerTest extends TestCase
{
    private BadgeComposer $badgeComposer;

    private string $inputFile = __DIR__ . "/test-input1.xml";
    private string $inputFile2 = __DIR__ . "/test-input2.xml";
    private string $outputFile = __DIR__ . "/../badges/output.svg";
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
        $method = (new ReflectionClass($this->badgeComposer))->getMethod('validateFiles');

        return $method->invoke($this->badgeComposer, $files, $output);
    }

    /**
     * Check if an exception is thrown when trying to validate files that do not exist.
     * @throws ReflectionException
     */
    public function testErrorIsThrownWhenInputFileDoesNotExist(): void
    {
        $this->expectException(Throwable::class);
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
        $this->expectException(Throwable::class);
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
        $method = (new ReflectionClass($this->badgeComposer))->getMethod('processFile');

        return $method->invoke($this->badgeComposer, $inputFile);
    }

    /**
     * @throws ReflectionException
     */
    public function testProcessTheCloverFileAndCalculateTheCoverage(): void
    {
        $this->processFile($this->inputFile);

        $this->assertEquals(43, $this->badgeComposer->getTotalCoverage());
    }

    /**
     * @throws ReflectionException
     */
    public function testProcessMultipleCloverFilesAndCalculateTheCoverage(): void
    {
        $this->processFile($this->inputFile);
        $this->processFile($this->inputFile2);

        $this->assertEquals(83, $this->badgeComposer->getTotalCoverage());
    }

    /**
     * @throws ReflectionException
     */
    public function testItThrowsExceptionWhenFileProcessingFails(): void
    {
        $this->expectException(Throwable::class);
        $this->expectExceptionMessage('Error reading file: invalid_file.xml');

        $this->processFile('invalid_file.xml');
    }

    /**
     * @throws ReflectionException
     */
    public function finalizeCoverage()
    {
        $method = (new ReflectionClass($this->badgeComposer))->getMethod('finalizeCoverage');

        return $method->invoke($this->badgeComposer);
    }

    /**
     * @throws ReflectionException
     */
    public function testFinalizeCoverageCalculatesAverageCoverage(): void
    {
        $this->finalizeCoverage();

        $outputFileContent = file_get_contents(__DIR__ . "/../badges/output.svg");
        $this->assertStringContainsString('#e05d44', $outputFileContent);

        $this->assertStringContainsString('unit', $outputFileContent);
    }

}
