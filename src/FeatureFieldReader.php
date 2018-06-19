<?php
declare(strict_types=1);

namespace edwrodrig\genbank_reader;

/**
 * Class FeatureFieldReader
 *
 * A feature field is a name for every entry in the {@see Features features} section.
 * Each {@see FeatureFieldReader::getField() field name} belongs to a section with name like source, CDS, gene.
 * @see https://www.ncbi.nlm.nih.gov/Sitemap/samplerecord.html#FeaturesSourceA
 * @package edwrodrig\genbank_reader
 */
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
     * @param StreamReader $stream
     * @throws exception\InvalidFeatureFieldException
     * @throws exception\InvalidStreamException
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

    /**
     * Get the location.
     *
     * Generally a range with some information about if it is complement or not
     * @return RangeReader
     */
    public function getLocation() : RangeReader {
        return $this->location;
    }

    /**
     * Get the next field
     *
     * See if the next name of a field
     * @param StreamReader $stream
     * @return null|string
     */
    static public function getNextField(StreamReader $stream) : ?string {
        $line = $stream->readLine();
        $stream->rollBack();

        return self::readField($line);
    }


    /**
     * Read the field part of a line.
     *
     * This get the {@see FeatureFieldReader::getField() field}.
     * @param string $line
     * @return null|string
     */
    static private function readField(string $line) : ?string {
        $field = trim(substr($line, 0, 21));

        if ( empty($field) )
            return null;

        return $field;
    }

    /**
     * Read the content part of the line
     * @param string $line
     * @return string
     */
    static private function readContent(string $line) : string {
        if ( $part = substr($line, 21) ) {
            return $part;
        } else {
            return '';
        }
    }

    /**
     * Parse a feature field section
     * @throws exception\InvalidFeatureFieldException
     * @throws exception\InvalidStreamException
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