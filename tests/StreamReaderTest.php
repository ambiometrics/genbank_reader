<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-06-18
 * Time: 15:47
 */

namespace test\edwrodrig\genbank_reader;

use edwrodrig\genbank_reader\StreamReader;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class StreamReaderTest extends TestCase
{

    /**
     * @var vfsStreamDirectory
     */
    private $root;

    public function setUp() {
        $this->root = vfsStream::setup();
    }

    /**
     * @throws \edwrodrig\genbank_reader\exception\InvalidStreamException
     */
    public function testHappyCase() {
        $filename =  $this->root->url() . '/test';

        file_put_contents($filename, <<<EOF
LINE1
LINE2
LINE3
EOF
        );
        $f = fopen($filename, 'r');


        $reader = new StreamReader($f);
        $this->assertEquals("LINE1\n", $reader->readLine());
        $this->assertEquals("LINE2\n", $reader->readLine());
        $this->assertFalse($reader->atEnd());
        $reader->rollBack();
        $this->assertEquals("LINE2\n", $reader->readLine());
        $reader->rollBack();
        $this->assertEquals("LINE2\n", $reader->readLine());
        $this->assertEquals("LINE3", $reader->readLine());
        $this->assertTrue($reader->atEnd());

    }
}
