<?php
declare(strict_types=1);

namespace edwrodrig\genbank_reader;


class FeatureFieldReader
{

    /**
     * @var StreamReader
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
     */
    public function __construct(StreamReader $stream) {
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

    static public function getNextField(StreamReader $stream) : ?string {
        $line = $stream->readLine();
        $stream->rollBack();

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
        $line = $this->stream->readLine();

        $this->field = self::readField($line);
        $this->location = new RangeReader(trim(self::readContent($line)));

        if ( is_null($this->field) ) {
            throw new exception\InvalidFeatureFieldException($line);
        }


        while ( !$this->stream->atEnd() ) {
            $line = $this->stream->readLine();

            $field = $this->readField($line);
            $content = $this->readContent($line);

            if ( is_null($field) ) {
                $this->content .= $content;

            } else {
                $this->stream->rollBack();
                break;
            }
        }
        $this->content = trim($this->content);
    }
}