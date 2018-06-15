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
     * @var FeaturePropertiesReader
     */
    private $properties;

    /**
     * HeaderFieldReader constructor.
     * @param $stream
     * @throws exception\InvalidFeatureFieldException
     */
    public function __construct(StreamReader $stream) {
        $this->stream = $stream;
        $this->parse();
    }

    /**
     * Get field
     * @return string
     */
    public function getField() : string {
        return $this->field;
    }

    public function getLocation() : RangeReader {
        return $this->location;
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

        $content = '';

        while ( !$this->stream->atEnd() ) {
            $line = $this->stream->readLine();

            $field = $this->readField($line);


            if ( is_null($field) ) {
                $content .= $this->readContent($line);

            } else {
                $this->stream->rollBack();
                break;
            }
        }

        $this->properties = new FeaturePropertiesReader($content);

    }

    /**
     * Get Properties
     * @return FeaturePropertiesReader
     */
    public function getProperties() : FeaturePropertiesReader {
        return $this->properties;
    }
}