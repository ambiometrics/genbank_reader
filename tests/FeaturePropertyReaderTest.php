<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 12-06-18
 * Time: 16:36
 */

namespace test\edwrodrig\genbank_reader;

use edwrodrig\genbank_reader\FeaturePropertyReader;
use edwrodrig\genbank_reader\StreamReader;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class FeaturePropertyReaderTest extends TestCase
{

    /**
     * @var vfsStreamDirectory
     */
    private $root;

    public function setUp() {
        $this->root = vfsStream::setup();
    }

    /**
     * @testWith ["organism", "Saccharomyces cerevisiae", "/organism=\"Saccharomyces cerevisiae\""]
     *            ["db_xref", "taxon:4932", "/db_xref=\"taxon:4932\""]
     *            ["codon_start", "3", "/codon_start=3"]
     *            ["protein_id", "AAA98665.1", "/protein_id=\"AAA98665.1\""]
     *            ["translation", "SSIYNGISTSGLDLNNGTIADMRQLGIVESYKLKRAVVSSASEAAEVLLRVDNIIRARPRTANRQHM", "/translation=\"SSIYNGISTSGLDLNNGTIADMRQLGIVESYKLKRAVVSSASEA\nAEVLLRVDNIIRARPRTANRQHM\""]
     *            ["function", "required for axial budding pattern of S.cerevisiae", "/function=\"required for axial budding pattern of S.\ncerevisiae\""]
     * @param $expectedField
     * @param $expectedContent
     * @param $line
     * @throws \edwrodrig\genbank_reader\exception\InvalidFeatureFieldException
     * @throws \edwrodrig\genbank_reader\exception\InvalidStreamException
     */
    public function testReadSingle($expectedField, $expectedContent, $line) {

        $stream = fopen('php://memory','r+');
        fwrite($stream, $line);
        rewind($stream);

        $properties = new FeaturePropertyReader(new StreamReader($stream));
        $this->assertEquals($expectedField, $properties->getField());
        $this->assertEquals($expectedContent, $properties->getContent());

        fclose($stream);
    }
}
