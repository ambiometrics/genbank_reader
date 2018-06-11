<?php
declare(strict_types=1);

namespace test\edwrodrig\genbank;

use edwrodrig\genbank\HeaderLineReader;
use PHPUnit\Framework\TestCase;

class HeaderLineReaderTest extends TestCase
{

    /**
     * @testWith ["DEFINITION", "Some description",  "DEFINITION  Some description"]
     *           ["ACCESSION", "1.2.3",  "ACCESSION   1.2.3"]
     *           ["AUTHORS", "Edwin Rodriguez",  "  AUTHORS   Edwin Rodriguez"]
     * @param null|string $expectedField
     * @param string $expectedContent
     * @param string $line
     */
    public function testLineRead(?string $expectedField, string $expectedContent, string $line) {
        $header = new HeaderLineReader($line);
        $this->assertEquals($expectedField, $header->getField());
        $this->assertEquals($expectedContent, $header->getContent());
    }

    /**
     * @testWith [true, "FEATURE    "]
     *           [true, "  FEATURE        "]
     *           [false, "DEFINITION    "]
     *           [false, "                        FEATURE    "]
     * @param bool $expected
     * @param string $line
     * @throws \edwrodrig\genbank\exception\InvalidHeaderLineFormatException
     */
    public function testIsEnd(bool $expected, string $line) {
        $header = new HeaderLineReader($line);
        $this->assertEquals($expected, $header->isEnd());
    }

    /**
     * @testWith [true, "                      sdgsfdg sfg sdfg    "]
     *           [true, "  "]
     *           [false, "DEFINITION            "]
     *           [false, "ACCESSION    "]
     * @param bool $expected
     * @param string $line
     * @throws \edwrodrig\genbank\exception\InvalidHeaderLineFormatException
     */
    public function testIsContinuation(bool $expected, string $line) {
        $header = new HeaderLineReader($line);
        $this->assertEquals($expected, $header->isContinuation());
    }

}
