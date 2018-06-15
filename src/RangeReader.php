<?php
declare(strict_types=1);

namespace edwrodrig\genbank_reader;

/**
 * Class RangeReader
 *
 * Clas to read a rango string
 *
 * The range string is some text that is present in the feature section
 * @package edwrodrig\genbank_reader
 */
class RangeReader
{
    private $original_text;

    private $complement = false;

    private $start;

    private $end;

    public function __construct(string $text) {
        $this->original_text = $text;
        $this->parse();
    }

    public function getOriginalText() : string {
        return $this->original_text;
    }

    public function getStart() : int {
        return $this->start;
    }

    public function getEnd() : int {
        return $this->end;
    }

    public function isComplement() : bool {
        return $this->complement;
    }

    private function parse() {

        if ( strpos($this->original_text, 'complement') === 0 ) {
            $this->complement = true;
        }

        if ( preg_match('/(\d+)\<?\.\.\>?(\d+)/', $this->original_text, $matches) ) {

            $this->start = intval($matches[1]);
            $this->end = intval($matches[2]);

        }

    }
}