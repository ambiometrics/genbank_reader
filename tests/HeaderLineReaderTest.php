<?php
declare(strict_types=1);

namespace test\edwrodrig\genbank;

use edwrodrig\genbank\HeaderLineReader;
use PHPUnit\Framework\TestCase;

class HeaderLineReaderTest extends TestCase
{
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
