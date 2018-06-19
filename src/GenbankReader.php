<?php
declare(strict_types=1);

namespace edwrodrig\genbank_reader;

/**
 * Genbank Reader
 *
 * This class read a Genbank file. Genbank file is a very common file format to store genomic data. But it is very bad for parsing it.
 *
 * The ORIGIN may be left blank, may appear as "Unreported," or may give a local pointer to the sequence start, usually involving an experimentally determined restriction cleavage site or the genetic locus (if available). This information is present only in older records.
 *
 * The sequence data begin on the line immediately below ORIGIN. To view/save the sequence data only, display the record in FASTA format. A description of FASTA format is accessible from the BLAST Web pages.
 *
 * @see https://www.ncbi.nlm.nih.gov/Sitemap/samplerecord.html
 * @package edwrodrig\genbank_reader
 */
class GenbankReader
{

    /**
     * @var StreamReader
     */
    private $stream;

    /**
     * @var bool|null|resource
     */
    private $handle = null;

    /**
     * @var HeaderReader
     */
    private $header;

    /**
     * @var FeaturesReader
     */
    private $features;

    /**
     * @var OriginReader
     */
    private $origin;

    /**
     * GenbankReader constructor.
     * @param string $filename
     * @throws exception\InvalidFeatureFieldException
     * @throws exception\InvalidHeaderFieldException
     * @throws exception\InvalidStreamException
     * @throws exception\OpenFileException
     */
    public function __construct(string $filename) {
        $this->handle = fopen($filename, 'r');
        if ( $this->handle === FALSE ) {
            throw new exception\OpenFileException($filename);
        }
        $this->stream = new StreamReader($this->handle);
        $this->parse();
    }

    /**
     * Closes the reader
     *
     * @internal
     * It closes the opened resources
     */
    public function __destruct()
    {
        if (is_resource($this->handle))
            fclose($this->handle);
    }

    /**
     * Parses the file
     * @throws exception\InvalidFeatureFieldException
     * @throws exception\InvalidHeaderFieldException
     */
    private function parse() {
        $this->header = new HeaderReader($this->stream);
        $this->features = new FeaturesReader($this->stream);
        $this->origin = new OriginReader($this->stream);
    }

    /**
     * Get the headers of the file
     *
     * @api
     * @see https://www.ncbi.nlm.nih.gov/Sitemap/samplerecord.html#LocusA
     * @return HeaderReader
     */
    public function getHeader() : HeaderReader {
        return $this->header;
    }

    /**
     * Get the features of the file
     *
     * @api
     * @see https://www.ncbi.nlm.nih.gov/Sitemap/samplerecord.html#FeaturesB
     * @return FeaturesReader
     */
    public function getFeatures() : FeaturesReader {
        return $this->features;
    }

    /**
     * Get the origin of the file
     *
     * The origin as the sequence data
     * @see https://www.ncbi.nlm.nih.gov/Sitemap/samplerecord.html#OriginB
     * @api
     * @return OriginReader
     */
    public function getOrigin() : OriginReader {
        return $this->origin;
    }



}
