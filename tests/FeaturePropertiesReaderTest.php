<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 14-06-18
 * Time: 11:12
 */

namespace test\edwrodrig\genbank_reader;

use edwrodrig\genbank_reader\FeaturePropertiesReader;
use PHPUnit\Framework\TestCase;

class FeaturePropertiesReaderTest extends TestCase
{
    public function testReadSingle() {

        $content = <<<EOF
/organism="Saccharomyces cerevisiae"
/db_xref="taxon:4932"
/chromosome="IX"
/map="9"
/translation="SSIYNGISTSGLDLNNGTIADMRQLGIVESYKLKRAVVSSASEA
AEVLLRVDNIIRARPRTANRQHM"
/codon_start=3
/product="TCP1-beta"
/protein_id="AAA98665.1"
/gene="AXL2"
/note="plasma membrane glycoprotein"
/function="required for axial budding pattern of S.
cerevisiae"
EOF;

        $properties = new FeaturePropertiesReader($content);
        $this->assertEquals("Saccharomyces cerevisiae", $properties->getOrganism());
        $this->assertEquals(["taxon:4932"], $properties->getDbXref());
        $this->assertEquals("IX", $properties->getChromosome());
        $this->assertEquals("9", $properties->getMap());
        $this->assertEquals('AXL2', $properties->getGene());
        $this->assertEquals('plasma membrane glycoprotein', $properties->getNote());
        $this->assertEquals('AAA98665.1', $properties->getProteinId());
        $this->assertEquals('TCP1-beta', $properties->getProduct());
        $this->assertEquals(3, $properties->getCodonStart());
        $this->assertEquals("SSIYNGISTSGLDLNNGTIADMRQLGIVESYKLKRAVVSSASEAAEVLLRVDNIIRARPRTANRQHM", $properties->getTranslation());

        $this->assertEquals([], $properties->getOthers());

    }

}
