<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-06-18
 * Time: 15:39
 */

namespace edwrodrig\genbank_reader;


class FeaturePropertyReader
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

    public function getContent() : string {
        return $this->content;
    }

    static public function getNextField(StreamReader $stream) : ?string {
        $line = $stream->readLine();
        $stream->rollBack();

        return self::readField($line);
    }

    static private function readField(string $line) : ?string {
        if ( $field = preg_match('/\/([^=]*)=(.*)/', $line, $matches) ) {
            return trim($matches[1]);
        } else {
            return null;
        }

    }

    static private function readContent(string $line) : string {
        if ( $field = preg_match('/\/([^=]*)=(.*)/', $line, $matches) ) {
            return $matches[2];
        } else {
            return $line;
        }
    }

    private function normalizeContent() {
        $this->content = trim($this->content);

        if ( strlen($this->content) < 2 ) return;

        $last_char = $this->content[strlen($this->content) - 1];
        if ( $this->content[0] == '"' && $last_char == '"' )
            $this->content = substr($this->content, 1, strlen($this->content) - 2);
    }

    /**
     * @throws exception\InvalidFeatureFieldException
     */
    private function parse() {
        $line = $this->stream->readLine();

        $this->field = self::readField($line);
        $this->content = trim(self::readContent($line));

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

        $this->normalizeContent();
    }
}