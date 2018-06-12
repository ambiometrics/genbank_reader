<?php
declare(strict_types=1);

namespace edwrodrig\genbank_reader;


class FeatureFieldReader
{

    /**
     * @var resource
     */
    private $stream;

    /**
     * @var null|string
     */
    private $field = null;

    /**
     * @var RangeReader
     */
    private $location;

    /**
     * @var string
     */
    private $content = '';

    /**
     * HeaderFieldReader constructor.
     * @param $stream
     * @throws exception\InvalidFeatureFieldException
     * @throws exception\InvalidStreamException
     */
    public function __construct($stream) {
        if ( !is_resource($stream) ) {
            throw new exception\InvalidStreamException;
        }
        $this->stream = $stream;
        $this->parse();
    }

    public function getField() : string {
        return $this->field;
    }

    public function getLocation() : RangeReader {
        return $this->location;
    }

    public function getContent() : string {
        return $this->content;
    }

    static public function getNextField($stream) : ?string {
        if ( !is_resource($stream) ) {
            throw new exception\InvalidStreamException;
        }

        $position = ftell($stream);
        $line = fgets($stream);
        fseek($stream, $position);

        return self::readField($line);

    }


    static private function readField(string $line) : ?string {
        $field = trim(substr($line, 0, 21));

        if ( empty($field) )
            return null;

        return $field;
    }

    static private function readContent(string $line) : string {
        if ( $part = substr($line, 21) ) {
            return $part;
        } else {
            return '';
        }
    }

    /**
     * @throws exception\InvalidFeatureFieldException
     */
    private function parse() {
        $line = fgets($this->stream);

        $this->field = self::readField($line);
        $this->location = new RangeReader(trim(self::readContent($line)));

        if ( is_null($this->field) ) {
            throw new exception\InvalidFeatureFieldException($line);
        }


        $position = ftell($this->stream);
        while ( $line = fgets($this->stream) ) {

            $field = $this->readField($line);
            $content = $this->readContent($line);

            if ( is_null($field) ) {
                $this->content .= $content;

            } else {
                fseek($this->stream, $position);
                break;
            }
            $position = ftell($this->stream);
        }
        $this->content = trim($this->content);
    }
}