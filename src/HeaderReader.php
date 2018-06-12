<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 11-06-18
 * Time: 15:52
 */

namespace edwrodrig\genbank;


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
     * @return Reference[]
     */
    public function getReferences() : array {
        return $this->references;
    }

    public function parse() {
        $field = null;
        $content = null;

        /**
         * @var $reference Reference
         */
        $reference = null;

        while ( $line = fgets($this->stream) ) {
            $line_reader = new HeaderLineReader($line);
            if ($line_reader->isContinuation()) {
                $content .= $line_reader->getContent();

            } else {

                if ( is_null($field) ) {

                } else if ($field == 'DEFINITION') {
                    $this->definition = trim($content);
                } else if ($field == 'LOCUS') {
                    $this->locus = trim($content);
                } else if ($field == 'VERSION' ) {
                    $this->version = trim($content);
                } else if ( $field == 'ORGANISM') {
                    $this->organism = trim($content);
                } else if ( $field == 'REFERENCE' ) {
                    if ( !is_null($reference))
                        $this->references[] = $reference;
                    $reference = new Reference();
                    $reference->setReference(trim($content));
                } else if ( $field == 'AUTHORS') {
                    $reference->setAuthors(trim($content));
                } else if ( $field == 'TITLE' ) {
                    $reference->setTitle(trim($content));
                } else if ( $field == 'JOURNAL' ) {
                    $reference->setJournal(trim($content));
                } else if ( $field == 'PUBMED') {
                    $reference->setPubmed(trim($content));
                }

                if ($line_reader->isEnd()) {
                    break;
                } else {
                    $field = $line_reader->getField();
                    $content = $line_reader->getContent();

                }
            }
        }

        if ( !is_null($reference))
            $this->references[] = $reference;
    }
}