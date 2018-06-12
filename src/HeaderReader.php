<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 11-06-18
 * Time: 15:52
 */

namespace edwrodrig\genbank_reader;


class HeaderReader
{

    /**
     * @var resource
     */
    private $stream;

    /**
     * @var null|string
     */
    private $definition = null;

    /**
     * @var null|string
     */
    private $locus = null;

    /**
     * @var null|string
     */
    private $version = null;

    /**
     * @var null|string
     */
    private $organism = null;

    /**
     * @var Reference[]
     */
    private $references = [];

    /**
     * HeaderParser constructor.
     * @param $stream
     * @throws exception\InvalidStreamException
     */
    public function __construct($stream) {
        if ( !is_resource($stream) ) {
            throw new exception\InvalidStreamException;
        }
        $this->stream = $stream;
        $this->parse();
    }

    public function getDefinition() : ?string {
        return $this->definition;
    }

    public function getLocus() : ?string {
        return $this->locus;
    }

    public function getVersion() : ?string {
        return $this->version;
    }

    public function getOrganism() : ?string {
        return $this->organism;
    }

    /**
     * @return HeaderReferenceReader[]
     */
    public function getReferences() : array {
        return $this->references;
    }

    /**
     * @throws exception\InvalidHeaderFieldException
     * @throws exception\InvalidStreamException
     */
    private function parse() {

        while ( $field = HeaderFieldReader::getNextField($this->stream) ) {

            if ( $field == 'FEATURES')
                break;

            if ( $field == 'REFERENCE')
                $this->references[] = new HeaderReferenceReader($this->stream);
            else {
                $reader = new HeaderFieldReader($this->stream);
                if ( $field == 'DEFINITION')  {
                    $this->definition = $reader->getContent();
                } else if ($field == 'LOCUS') {
                    $this->locus = $reader->getContent();
                } else if ($field == 'VERSION' ) {
                    $this->version = $reader->getContent();
                } else if ( $field == 'ORGANISM') {
                    $this->organism = $reader->getContent();
                }
            }
        }

    }
}