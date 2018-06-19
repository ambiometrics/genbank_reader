<?php
declare(strict_types=1);

namespace edwrodrig\genbank_reader;

/**
 * Class RangeReader
 *
 * Class to read a range string
 *
 * The range string is some text that is present in the feature section.
 * @see https://www.ncbi.nlm.nih.gov/Sitemap/samplerecord.html#BaseSpanB
 * @package edwrodrig\genbank_reader
 */
class RangeReader
{
    /**
     * @var string
     */
    private $original_text;

    /**
     * @var bool
     */
    private $complement = false;

    /**
     * @var int
     */
    private $start;

    /**
     * @var int
     */
    private $end;

    /**
     * RangeReader constructor.
     *
     * Build a range reader from text
     * @param string $text
     */
    public function __construct(string $text) {
        $this->original_text = $text;
        $this->parse();
    }

    /**
     * Get the original range text
     *
     * Some stuff in the string are ignored so it is useful to see it if you want to make some other processing
     * @api
     * @return string
     */
    public function getOriginalText() : string {
        return $this->original_text;
    }

    /**
     * Get the start base
     * @api
     * @return int
     */
    public function getStart() : int {
        return $this->start;
    }

    /**
     * Get the end base
     * @api
     * @return int
     */
    public function getEnd() : int {
        return $this->end;
    }

    /**
     * Is the range in the complementary strand
     *
     * indicates that the feature is on the complementary strand
     * @api
     * @return bool
     */
    public function isComplement() : bool {
        return $this->complement;
    }

    /**
     * Internal function that parses the range string
     */
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