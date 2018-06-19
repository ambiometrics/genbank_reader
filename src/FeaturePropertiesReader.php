<?php
declare(strict_types=1);

namespace edwrodrig\genbank_reader;

/**
 * Class FeaturePropertiesReader
 *
 * This is a class to wraps the parsing of all {@see FeatureFieldReader feature} {@see FeaturePropertyReader properties}
 * From this class you must access to the field properties like
 * {@see FeaturePropertiesReader::getDbXref() db_xref}, {@see FeaturePropertiesReader::getOrganism() organism}, etc.
 *
 *
 * @package edwrodrig\genbank_reader
 */
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
     * @var null|string
     */
    private $function = null;


    /**
     * @var null|string
     */
    private $locus_tag = null;

    /**
     * @var array
     */
    private $others = [];

    /**
     * FeaturePropertiesReader constructor.
     * @param string $raw_properties
     * @throws exception\InvalidFeatureFieldException
     * @throws exception\InvalidStreamException
     */
    public function __construct(string $raw_properties) {
        $stream = $f = fopen('php://memory', 'r+');
        fwrite($stream, $raw_properties);
        rewind($stream);
        $this->stream = new StreamReader($stream);
        $this->parse();
    }

    /**
     * Parse the feature properties
     *
     * @throws exception\InvalidFeatureFieldException
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
            } else if ( $field == 'function') {
                $this->function = $reader->getContent();
            } else if ( $field == 'locus_tag') {
                $this->locus_tag = $reader->getContent();
            } else {
                $this->othesr[$reader->getField()][] = $reader->getContent();
            }
        }
    }

    /**
     * Get the database cross reference
     * @return array
     */
    public function getDbXref() : array {
        return $this->db_xref;
    }

    /**
     * Get gene
     * @return null|string
     */
    public function getGene() : ?string {
        return $this->gene;
    }

    /**
     * Get codon start
     * @return int|null
     */
    public function getCodonStart() : ?int {
        return $this->codon_start;
    }

    /**
     * Get note
     * @return null|string
     */
    public function getNote() : ?string {
        return $this->note;
    }

    /**
     * Get the protein id
     * @return null|string
     */
    public function getProteinId() : ?string {
        return $this->protein_id;
    }

    /**
     * Get the product
     * @return null|string
     */
    public function getProduct() : ?string {
        return $this->product;
    }

    /**
     * Get function
     * @return null|string
     */
    public function getFunction() : ?string {
        return $this->function;
    }

    /**
     * Get locus tag
     * @return null|string
     */
    public function getLocusTag() : ?string {
        return $this->locus_tag;
    }

    /**
     * Get organism
     * @return null|string
     */
    public function getOrganism(): ?string
    {
        return $this->organism;
    }

    /**
     * Get Chromosome
     * @return null|string
     */
    public function getChromosome(): ?string
    {
        return $this->chromosome;
    }

    /**
     * Get map
     * @return null|string
     */
    public function getMap(): ?string
    {
        return $this->map;
    }

    /**
     * Get translation
     * @return null|string
     */
    public function getTranslation(): ?string
    {
        return $this->translation;
    }

    /**
     * Get other information that is not commonly captured
     *
     * @return array A associative array with other values
     */
    public function getOthers(): array {
        return $this->others;
    }

}