<?php
declare(strict_types=1);

namespace edwrodrig\genbank_reader;


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

    public function getField() : string {
        return $this->field;
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
        $field = trim(substr($line, 0, 12));

        if ( empty($field) )
            return null;

        return $field;
    }

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