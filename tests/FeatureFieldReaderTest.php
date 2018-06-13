<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 12-06-18
 * Time: 16:36
 */

namespace test\edwrodrig\genbank_reader;

use edwrodrig\genbank_reader\FeatureFieldReader;
use edwrodrig\genbank_reader\StreamReader;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class FeatureFieldReaderTest extends TestCase
{

    /**
     * @var vfsStreamDirectory
     */
    private $root;

    public function setUp() {
        $this->root = vfsStream::setup();
    }

    /**
     * @throws \edwrodrig\genbank_reader\exception\InvalidFeatureFieldException
     * @throws \edwrodrig\genbank_reader\exception\InvalidStreamException
     * @throws \edwrodrig\genbank_reader\exception\InvalidHeaderFieldException
     */
    public function testReadSingle() {
        $filename =  $this->root->url() . '/test';

        file_put_contents($filename, <<<EOF
     source          1..5028
                     /organism="Saccharomyces cerevisiae"
                     /db_xref="taxon:4932"
                     /chromosome="IX"
                     /map="9"
EOF
        );
        $f = fopen($filename, 'r');


        $header = new FeatureFieldReader(new StreamReader($f));
        $this->assertEquals("source", $header->getField());
        $this->assertEquals(1, $header->getLocation()->getStart());
        $this->assertEquals(5028, $header->getLocation()->getEnd());
        $this->assertEquals(false, $header->getLocation()->isComplement());
        $this->assertEquals("/organism=\"Saccharomyces cerevisiae\"\n/db_xref=\"taxon:4932\"\n/chromosome=\"IX\"\n/map=\"9\"", $header->getContent());

    }
}
