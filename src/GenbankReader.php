<?php

namespace edwrodrig\genbank_reader;
use IteratorAggregate;

/**
 * @see https://www.ncbi.nlm.nih.gov/Sitemap/samplerecord.html
 * @package edwrodrig\genbank_reader
 */
class GenbankReader implements IteratorAggregate
{

    /**
     * @var bool|null|resource
     */
    public $stream = null;

    /**
     * @var HeaderReader
     */
    public $header;


    /**
     * FileParser constructor.
     * @param string $filename
     * @throws exception\InvalidHeaderLineFormatException
     * @throws exception\InvalidStreamException
     * @throws exception\OpenFileException
     */
    public function __construct(string $filename) {
        $this->stream = fopen($filename, 'r');
        if ( $this->stream === FALSE ) {
            throw new exception\OpenFileException($filename);
        }
        $this->header = new HeaderReader($this->stream);
    }

    public function __destruct()
    {
        if (is_null($this->stream)) return;
        fclose($this->stream);
    }

    /**
     * Get the headers of the line
     * @return HeaderReader
     */
    public function getHeaders() : HeaderReader {
        return $this->header;
    }

    /**
     * Convert a raw line in a array of columns.
     *
     * You can get the column metadata with {@see HeaderParser::getMetricByColummn()}
     * @see GenbankReader::getHeaders() to get the header class
     * @param string $line
     * @return array
     */
    private function parseDataLine(string $line) : array {
        $line = mb_convert_encoding($line, 'UTF-8');
        $line = trim($line);
        $tokens = preg_split('/\s+/', $line);
        return $tokens;
    }

    public function getIterator() {
        while ($line = fgets($this->stream)) {
            $data = $this->parseDataLine($line);
            yield $data;
        }
    }


}
