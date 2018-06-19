<?php
declare(strict_types=1);

namespace edwrodrig\genbank_reader;

/**
 * Class FeaturePropertyReader
 *
 * A feature property is a property owned by a features like db_xref. Generally are in the following format \db_xref=""
 *
 * @see https://www.ncbi.nlm.nih.gov/Sitemap/samplerecord.html#TaxonB
 * @package edwrodrig\genbank_reader
 */
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

    /**
     * Get name of the property
     * @return string
     */
    public function getField() : string {
        return $this->field;
    }

    /**
     * Get the content of the property
     * @return string
     */
    public function getContent() : string {
        return $this->content;
    }

    /**
     * Get the name of the next field.
     *
     * This is useful to anticipate the next steps in the parsing process
     * @param StreamReader $stream
     * @return null|string
     */
    static public function getNextField(StreamReader $stream) : ?string {
        $line = $stream->readLine();
        $stream->rollBack();

        return self::readField($line);
    }

    /**
     * Get a field name
     *
     * Parse a line and get the name part. between \ and =.
     * If the line is not a start return null. The start is the line with the field name
     * @param string $line
     * @return null|string
     */
    static private function readField(string $line) : ?string {
        if ( $field = preg_match('/\/([^=]*)=(.*)/', $line, $matches) ) {
            return trim($matches[1]);
        } else {
            return null;
        }

    }

    /**
     * Get a content name
     *
     * Get the content name from a string, or all the string at the right side of the =
     * If the line is not a start then return all the line. The start is the line with the field name
     * @param string $line
     * @return string
     */
    static private function readContent(string $line) : string {
        if ( $field = preg_match('/\/([^=]*)=(.*)/', $line, $matches) ) {
            return $matches[2];
        } else {
            return $line;
        }
    }

    /**
     * Normalize the retrieved content
     *
     * This is used in the {@see FeaturePropertyReader::parse() parse} process.
     */
    private function normalizeContent() {
        $this->content = trim($this->content);

        if ( strlen($this->content) < 2 ) return;

        $last_char = $this->content[strlen($this->content) - 1];
        if ( $this->content[0] == '"' && $last_char == '"' )
            $this->content = substr($this->content, 1, strlen($this->content) - 2);
    }

    /**
     * Parse the feature property
     *
     * @uses
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