<?php
declare(strict_types=1);

namespace test\edwrodrig\genbank_reader;

use edwrodrig\genbank_reader\HeaderReader;
use edwrodrig\genbank_reader\StreamReader;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class HeaderReaderTest extends TestCase
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
DEFINITION  hola como te va
            muy bien saludos
LOCUS       some locus
FEATURES
EOF
        );
        $f = fopen($filename, 'r');


        $header = new HeaderReader(new StreamReader($f));
        $this->assertEquals("hola como te va\nmuy bien saludos", $header->getDefinition());
        $this->assertEquals('some locus', $header->getLocus());
    }

    /**
     * @throws \edwrodrig\genbank_reader\exception\InvalidStreamException
     */
    public function testReference() {
        $filename =  $this->root->url() . '/test';

        file_put_contents($filename, <<<EOF
LOCUS       SCU49845     5028 bp    DNA             PLN       21-JUN-1999
DEFINITION  Saccharomyces cerevisiae TCP1-beta gene, partial cds, and Axl2p
            (AXL2) and Rev7p (REV7) genes, complete cds.
ACCESSION   U49845
VERSION     U49845.1  GI:1293613
KEYWORDS    .
SOURCE      Saccharomyces cerevisiae (baker's yeast)
  ORGANISM  Saccharomyces cerevisiae
            Eukaryota; Fungi; Ascomycota; Saccharomycotina; Saccharomycetes;
            Saccharomycetales; Saccharomycetaceae; Saccharomyces.
REFERENCE   1  (bases 1 to 5028)
  AUTHORS   Torpey,L.E., Gibbs,P.E., Nelson,J. and Lawrence,C.W.
  TITLE     Cloning and sequence of REV7, a gene whose function is required for
            DNA damage-induced mutagenesis in Saccharomyces cerevisiae
  JOURNAL   Yeast 10 (11), 1503-1509 (1994)
  PUBMED    7871890
REFERENCE   2  (bases 1 to 5028)
  AUTHORS   Roemer,T., Madden,K., Chang,J. and Snyder,M.
  TITLE     Selection of axial growth sites in yeast requires Axl2p, a novel
            plasma membrane glycoprotein
  JOURNAL   Genes Dev. 10 (7), 777-793 (1996)
  PUBMED    8846915
REFERENCE   3  (bases 1 to 5028)
  AUTHORS   Roemer,T.
  TITLE     Direct Submission
  JOURNAL   Submitted (22-FEB-1996) Terry Roemer, Biology, Yale University, New
            Haven, CT, USA
FEATURES
EOF
        );
        $f = fopen($filename, 'r');


        $header = new HeaderReader(new StreamReader($f));
        $this->assertEquals("Saccharomyces cerevisiae TCP1-beta gene, partial cds, and Axl2p\n(AXL2) and Rev7p (REV7) genes, complete cds.", $header->getDefinition());
        $this->assertEquals('SCU49845     5028 bp    DNA             PLN       21-JUN-1999', $header->getLocus());

        $references = $header->getReferences();
        $this->assertCount(3, $references);
        $reference = $references[0];
        $this->assertEquals('1  (bases 1 to 5028)', $reference->getReference());
        $this->assertEquals('Torpey,L.E., Gibbs,P.E., Nelson,J. and Lawrence,C.W.', $reference->getAuthors());
        $this->assertEquals('Yeast 10 (11), 1503-1509 (1994)', $reference->getJournal());
        $this->assertEquals('7871890', $reference->getPubmed());

        $reference = $references[1];
        $this->assertEquals('2  (bases 1 to 5028)', $reference->getReference());
        $this->assertEquals('Roemer,T., Madden,K., Chang,J. and Snyder,M.', $reference->getAuthors());
        $this->assertEquals('Genes Dev. 10 (7), 777-793 (1996)', $reference->getJournal());
        $this->assertEquals('8846915', $reference->getPubmed());

        $reference = $references[2];
        $this->assertEquals('3  (bases 1 to 5028)', $reference->getReference());
        $this->assertEquals("Roemer,T.", $reference->getAuthors());
        $this->assertEquals("Direct Submission", $reference->getTitle());
        $this->assertEquals("Submitted (22-FEB-1996) Terry Roemer, Biology, Yale University, New\nHaven, CT, USA", $reference->getJournal());
        $this->assertNull($reference->getPubmed());

    }
}
