<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 14-06-18
 * Time: 10:54
 */

namespace edwrodrig\genbank_reader;


class FeaturePropertiesReader
{

    /**
     * @var StreamReader
     */
    private $stream;

    /**
     * @var string[]
     */
    private $db_xref = [];

    /**
     * @var null|string
     */
    private $organism = null;

    /**
     * @var null|string
     */
    private $chromosome = null;

    /**
     * @var null|string
     */
    private $map = null;

    /**
     * @var null|string
     */
    private $translation = null;

    /**
     * @var null|string
     */
    private $gene = null;

    /**
     * @var null|int
     */
    private $codon_start = null;

    /**
     * @var null|string
     */
    private $note = null;

    /**
     * @var null|string
     */
    private $protein_id = null;

    /**
     * @var null|string
     */
    private $product = null;

    /**
     * @var array
     */
    private $others = [];

    public function __construct(string $raw_properties) {
        $stream = $f = fopen('php://memory', 'r+');
        fwrite($stream, $raw_properties);
        rewind($stream);
        $this->stream = new StreamReader($stream);
        $this->parse();
    }

    /**
     * @throws exception\InvalidFeatureFieldException
     * @throws exception\InvalidStreamException
     */
    private function parse() {

        while ( $field = FeaturePropertyReader::getNextField($this->stream) ) {

            $reader = new FeaturePropertyReader($this->stream);
            if ( $field == 'db_xref') {
                $this->db_xref[] = $reader->getContent();
            } else if ( $field == 'translation' ) {
                $this->translation = $reader->getContent();
            } else if ( $field == 'organism') {
                $this->organism = $reader->getContent();
            } else if ( $field == 'chromosome') {
                $this->chromosome = $reader->getContent();
            } else if ( $field == 'map') {
                $this->map = $reader->getContent();
            } else if ( $field == 'gene') {
                $this->gene = $reader->getContent();
            } else if ( $field == 'codon_start') {
                $this->codon_start = intval($reader->getContent());
            } else if ( $field == 'note' ) {
                $this->note = $reader->getContent();
            } else if ( $field == 'product') {
                $this->product = $reader->getContent();
            } else if ( $field == 'protein_id') {
                $this->protein_id = $reader->getContent();
            } else {
                $this->othesr[$reader->getField()][] = $reader->getContent();
            }
        }
    }

    public function getDbXref() : array {
        return $this->db_xref;
    }

    public function getGene() : ?string {
        return $this->gene;
    }

    public function getCodonStart() : ?int {
        return $this->codon_start;
    }

    public function getNote() : ?string {
        return $this->note;
    }

    public function getProteinId() : ?string {
        return $this->protein_id;
    }

    public function getProduct() : ?string {
        return $this->product;
    }

    /**
     * @return null|string
     */
    public function getOrganism(): ?string
    {
        return $this->organism;
    }

    /**
     * @return null|string
     */
    public function getChromosome(): ?string
    {
        return $this->chromosome;
    }

    /**
     * @return null|string
     */
    public function getMap(): ?string
    {
        return $this->map;
    }

    /**
     * @return null|string
     */
    public function getTranslation(): ?string
    {
        return $this->translation;
    }

    public function getOthers(): array {
        return $this->others;
    }

}