<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 11-06-18
 * Time: 16:06
 */

namespace edwrodrig\genbank;


class HeaderLineReader
{
    /**
     * @var null|string
     */
    private $field;

    /**
     * @var string
     */
    private $content;

    /**
     * @var bool
     */
    private $nested = false;

    const VALID_FIELDS = [
        'LOCUS',
        'DEFINITION',
        'ACCESSION',
        'VERSION',
        'KEYWORDS',
        'SOURCE',
        'ORGANISM',
        'REFERENCE',
        'AUTHORS',
        'TITLE',
        'JOURNAL',
        'PUBMED'
    ];

    const TERMINATION_FIELD = 'FEATURES';

    public function __construct(string $line) {
        $this->field = $this->readField($line);
        $this->content = $this->readContent($line);
    }

    /**
     * If the current header line is a continuation of a previous line
     *
     * Or is part of a multiline value
     * @return bool
     */
    public function isContinuation() : bool {
        return is_null($this->field);
    }

    public function getField() : ?string {
        return $this->field;
    }

    public function getContent() : string {
        return $this->content;
    }

    public function isEnd() : bool {
        return $this->field == self::TERMINATION_FIELD;
    }

    public function isNested() : bool {
        return $this->nested;
    }

    /**
     * @param string $line
     * @return null|string
     * @throws exception\InvalidHeaderFieldException
     */
    private function readField(string $line) : ?string {
        $field = trim(substr($line, 0, 12));

        if ( empty($field) )
            return null;

        if ( empty(trim($line[0])) ) {
            $this->nested = true;
        }

        if ( in_array($field, self::VALID_FIELDS) ) {
            return $field;
        } else if ( $field == self::TERMINATION_FIELD ) {
            return $field;
        } else {
            throw new exception\InvalidHeaderFieldException($field);
        }
    }

    private function readContent(string $line) : string {
        if ( $part = substr($line, 12) ) {
            return $part;
        } else {
            return '';
        }
    }

}