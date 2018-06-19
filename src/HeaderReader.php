<?php
declare(strict_types=1);

namespace edwrodrig\genbank_reader;


/**
 * Class HeaderReader
 *
 * This class read the header section of a Genbank file.
 * This class should not be created by an user.
 * The only interaction that a final user may have is by their getters.
 *
 * @see https://www.ncbi.nlm.nih.gov/Sitemap/samplerecord.html#LocusB
 * @package edwrodrig\genbank_reader
 */
class HeaderReader
{

    /**
     * @var StreamReader
     */
    private $stream;

    /**
     * @var null|string
     */
    private $definition = null;

    /**
     * @var null|string
     */
    private $locus = null;

    /**
     * @var null|string
     */
    private $version = null;

    /**
     * @var null|string
     */
    private $organism = null;

    /**
     * @var null|string
     */
    private $source = null;

    /**
     * @var Reference[]
     */
    private $references = [];

    /**
     * HeaderReader constructor.
     *
     * @uses HeaderReader::parse()
     * @param StreamReader $stream
     * @throws exception\InvalidHeaderFieldException
     */
    public function __construct(StreamReader $stream) {
        $this->stream = $stream;
        $this->parse();
    }

    /**
     * Get the definition
     *
     * Brief description of sequence; includes information such as source organism, gene name/protein name, or some description of the sequence's function (if the sequence is non-coding). If the sequence has a coding region (CDS), description may be followed by a completeness qualifier, such as "complete cds". (See GenBank release notes section 3.4.5 for more info.)
     * @see https://www.ncbi.nlm.nih.gov/Sitemap/samplerecord.html#DefinitionB
     * @return null|string
     */
    public function getDefinition() : ?string {
        return $this->definition;
    }

    /**
     * Get the locus tag
     *
     * The LOCUS field contains a number of different data elements, including locus name, sequence length, molecule type, GenBank division, and modification date. Each element is described below.
     * @see https://www.ncbi.nlm.nih.gov/Sitemap/samplerecord.html#LocusB
     * @return null|string
     */
    public function getLocus() : ?string {
        return $this->locus;
    }

    /**
     * Get the version
     *
     * A nucleotide sequence identification number that represents a single, specific sequence in the GenBank database. This identification number uses the accession.version format implemented by GenBank/EMBL/DDBJ in February 1999.
     *
     * If there is any change to the sequence data (even a single base), the version number will be increased, e.g., U12345.1 → U12345.2, but the accession portion will remain stable.
     *
     * The accession.version system of sequence identifiers runs parallel to the GI number system, i.e., when any change is made to a sequence, it receives a new GI number AND an increase to its version number.
     * @see https://www.ncbi.nlm.nih.gov/Sitemap/samplerecord.html#VersionB
     * @return null|string
     */
    public function getVersion() : ?string {
        return $this->version;
    }

    /**
     * Get the organism
     *
     * The formal scientific name for the source organism (genus and species, where appropriate) and its lineage, based on the phylogenetic classification scheme used in the NCBI Taxonomy Database. If the complete lineage of an organism is very long, an abbreviated lineage will be shown in the GenBank record and the complete lineage will be available in the Taxonomy Database. (See also the /db_xref=taxon:nnnn Feature qualifer, below.)
     * @see https://www.ncbi.nlm.nih.gov/Sitemap/samplerecord.html#OrganismB
     * @return null|string
     */
    public function getOrganism() : ?string {
        return $this->organism;
    }

    /**
     * Get the source
     *
     * Free-format information including an abbreviated form of the organism name, sometimes followed by a molecule type. (See section 3.4.10 of the GenBank release notes for more info.)
     * @see https://www.ncbi.nlm.nih.gov/Sitemap/samplerecord.html#SourceB
     * @return null|string
     */
    public function getSource() : ?string {
        return $this->source;
    }

    /**
     * Get the references
     *
     * Publications by the authors of the sequence that discuss the data reported in the record. References are automatically sorted within the record based on date of publication, showing the oldest references first.
     * @see https://www.ncbi.nlm.nih.gov/Sitemap/samplerecord.html#ReferenceB
     * @return HeaderReferenceReader[]
     */
    public function getReferences() : array {
        return $this->references;
    }

    /**
     * Parse the header section
     * @throws exception\InvalidHeaderFieldException
     */
    private function parse() {

        while ( $field = HeaderFieldReader::getNextField($this->stream) ) {

            if ( $field == 'FEATURES')
                break;

            if ( $field == 'REFERENCE')
                $this->references[] = new HeaderReferenceReader($this->stream);
            else {
                $reader = new HeaderFieldReader($this->stream);
                if ( $field == 'DEFINITION')  {
                    $this->definition = $reader->getContent();
                } else if ($field == 'LOCUS') {
                    $this->locus = $reader->getContent();
                } else if ($field == 'VERSION' ) {
                    $this->version = $reader->getContent();
                } else if ( $field == 'ORGANISM') {
                    $this->organism = $reader->getContent();
                } else if ( $field == 'SOURCE') {
                    $this->source = $reader->getContent();
                }
            }
        }

    }
}