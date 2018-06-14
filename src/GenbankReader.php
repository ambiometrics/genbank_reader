<?php
declare(strict_types=1);

namespace edwrodrig\genbank_reader;

/**
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
     * FileParser constructor.
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

    public function __destruct()
    {
        if (is_resource($this->handle))
            fclose($this->handle);
    }

    /**
     * @throws exception\InvalidFeatureFieldException
     * @throws exception\InvalidHeaderFieldException
     * @throws exception\InvalidStreamException
     */
    private function parse() {
        $this->header = new HeaderReader($this->stream);
        $this->features = new FeaturesReader($this->stream);
        $this->origin = new OriginReader($this->stream);
    }

    /**
     * Get the headers of the file
     * @return HeaderReader
     */
    public function getHeader() : HeaderReader {
        return $this->header;
    }

    /**
     * Get the features of the file
     * @return FeaturesReader
     */
    public function getFeatures() : FeaturesReader {
        return $this->features;
    }

    /**
     * Get the origin of the file
     *
     * The origin as the sequence data
     * @return OriginReader
     */
    public function getOrigin() : OriginReader {
        return $this->origin;
    }



}
