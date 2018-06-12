<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 12-06-18
 * Time: 17:00
 */

namespace test\edwrodrig\genbank;

use edwrodrig\genbank\RangeReader;
use PHPUnit\Framework\TestCase;

class RangeReaderTest extends TestCase
{
    /**
     * @testWith    [1, 2, false, "1..2"]
     *              [1, 5028, false, "1..5028"]
     *              [1, 206, false, "<1..206"]
     *              [687, 3158, false, "687..3158"]
     *              [3300, 4037, true, "complement(3300..4037)"]
     * @param int $expectedStart
     * @param int $expectedEnd
     * @param bool $expectedIsComplement
     * @param string $input
     */
    public function testParse(int $expectedStart, int $expectedEnd, bool $expectedIsComplement, string $input) {
        $range = new RangeReader($input);
        $this->assertEquals($expectedStart, $range->getStart());
        $this->assertEquals($expectedEnd, $range->getEnd());
        $this->assertEquals($expectedIsComplement, $range->isComplement());
    }
}
