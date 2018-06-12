<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 12-06-18
 * Time: 14:50
 */

namespace test\edwrodrig\genbank;

use edwrodrig\genbank\HeaderFieldReader;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class HeaderFieldReaderTest extends TestCase
{

    /**
     * @var vfsStreamDirectory
     */
    private $root;

    public function setUp() {
        $this->root = vfsStream::setup();
    }

    public function testReadSingle() {
        $filename =  $this->root->url() . '/test';

        file_put_contents($filename, <<<EOF
  ORGANISM  Saccharomyces cerevisiae
            Eukaryota; Fungi; Ascomycota; Saccharomycotina; Saccharomycetes;
            Saccharomycetales; Saccharomycetaceae; Saccharomyces.
EOF
        );
        $f = fopen($filename, 'r');


        $header = new HeaderFieldReader($f);
        $this->assertEquals("ORGANISM", $header->getField());
        $this->assertEquals("Saccharomyces cerevisiae\nEukaryota; Fungi; Ascomycota; Saccharomycotina; Saccharomycetes;\nSaccharomycetales; Saccharomycetaceae; Saccharomyces.", $header->getContent());

    }

    public function testReadDouble() {
        $filename =  $this->root->url() . '/test';

        file_put_contents($filename, <<<EOF
  ORGANISM  Saccharomyces cerevisiae
            Eukaryota; Fungi; Ascomycota; Saccharomycotina; Saccharomycetes;
            Saccharomycetales; Saccharomycetaceae; Saccharomyces.
FEATURES
EOF
        );
        $f = fopen($filename, 'r');


        $header = new HeaderFieldReader($f);
        $this->assertEquals("ORGANISM", $header->getField());
        $this->assertEquals("Saccharomyces cerevisiae\nEukaryota; Fungi; Ascomycota; Saccharomycotina; Saccharomycetes;\nSaccharomycetales; Saccharomycetaceae; Saccharomyces.", $header->getContent());

        $this->assertEquals("FEATURES", fgets($f));
    }

    /**
     * @testWith ["DEFINITION", "Some description",  "DEFINITION  Some description"]
     *           ["ACCESSION", "1.2.3",  "ACCESSION   1.2.3"]
     *           ["AUTHORS", "Edwin Rodriguez",  "  AUTHORS   Edwin Rodriguez"]
     * @param null|string $expectedField
     * @param string $expectedContent
     * @param string $line
     * @throws \edwrodrig\genbank\exception\InvalidHeaderFieldException
     * @throws \edwrodrig\genbank\exception\InvalidStreamException
     */
    public function testLineRead(?string $expectedField, string $expectedContent, string $line) {
        $filename =  $this->root->url() . '/test';

        file_put_contents($filename, $line);
        $f = fopen($filename, 'r');


        $header = new HeaderFieldReader($f);
        $this->assertEquals($expectedField, $header->getField());
        $this->assertEquals($expectedContent, $header->getContent());

    }

    public function testGetNextField() {
        $filename =  $this->root->url() . '/test';

        file_put_contents($filename, 'DEFINITION');
        $f = fopen($filename, 'r');

        $this->assertEquals('DEFINITION', HeaderFieldReader::getNextField($f));
    }
}
