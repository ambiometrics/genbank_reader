<?php
declare(strict_types=1);

namespace edwrodrig\genbank_reader;

/**
 * Class HeaderFieldReader
 * @package edwrodrig\genbank_reader
 */
class HeaderFieldReader
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
     * @var null|string
     */
    private $content = '';

    /**
     * HeaderFieldReader constructor.
     * @param StreamReader $stream
     * @throws exception\InvalidHeaderFieldException
     */
    public function __construct(StreamReader $stream) {
        $this->stream = $stream;
        $this->parse();
    }

    /**
     * Get the field name
     *
     * This get the header field name
     * @return string
     */
    public function getField() : string {
        return $this->field;
    }

    /**
     * Get the content of the field
     * @return string
     */
    public function getContent() : string {
        return $this->content;
    }

    /**
     * Foresse the next field of the stream
     * @param StreamReader $stream
     * @return null|string
     */
    static public function getNextField(StreamReader $stream) : ?string {

        $line = $stream->readLine();
        $stream->rollBack();

        return self::readField($line);

    }

    /**
     * Read the field
     * @param string $line
     * @return null|string
     */
    static private function readField(string $line) : ?string {
        $field = trim(substr($line, 0, 12));

        if ( empty($field) )
            return null;

        return $field;
    }

    /**
     * @param string $line
     * @return string
     */
    static private function readContent(string $line) : string {
        if ( $part = substr($line, 12) ) {
            return $part;
        } else {
            return '';
        }
    }

    /**
     * @throws exception\InvalidHeaderFieldException
     */
    private function parse() {
        $line = $this->stream->readLine();
        $this->field = self::readField($line);
        $this->content = self::readContent($line);

        if ( is_null($this->field) ) {
            throw new exception\InvalidHeaderFieldException($line);
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