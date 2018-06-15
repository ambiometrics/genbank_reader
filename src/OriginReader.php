<?php
declare(strict_types=1);


namespace edwrodrig\genbank_reader;


/**
 * Class OriginReader
 * This class read the origin section of the file
 *
 * @see
 * @package edwrodrig\genbank_reader
 */
class OriginReader
{
    /**
     * @var StreamReader
     */
    private $stream;

    /**
     * @var string
     */
    private $sequence = '';

    /**
     * OriginReader constructor.
     *
     * @param StreamReader $stream
     */
    public function __construct(StreamReader $stream) {
        $this->stream = $stream;
        $this->parse();
    }

    public function getSequence() : string {
        return $this->sequence;
    }

    private function parse() {
        $this->stream->readLine();

        while ( ! $this->stream->atEnd() ) {
            $line = $this->stream->readLine();
            $this->sequence .= preg_replace('/[^a-zA-Z]/', '', $line);
        }
    }
}