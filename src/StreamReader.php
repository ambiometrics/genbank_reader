<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-06-18
 * Time: 15:42
 */

namespace edwrodrig\genbank_reader;


class StreamReader
{
    /**
     * @var resource
     */
    private $stream;

    private $last_position = 0;

    /**
     * StreamReader constructor.
     * @param $stream
     * @throws exception\InvalidStreamException
     */
    public function __construct($stream)
    {
        if (!is_resource($stream)) {
            throw new exception\InvalidStreamException;
        }
        $this->stream = $stream;
    }

    /**
     * Read a line
     * @return string
     */
    public function readLine() : string {
        $this->last_position = ftell($this->stream);
        if ( $line = fgets($this->stream) ) {
            return $line;
        } else {
            return '';
        }

    }

    /**
     * If the current stream is at end
     * @return bool
     */
    public function atEnd() : bool {
        return feof($this->stream);
    }

    /**
     * Rollback the last line read.
     *
     * Just rollback the last read. If you call multiple times is the same as rollback the last read
     */
    public function rollBack() {
        fseek($this->stream, $this->last_position);
    }
}