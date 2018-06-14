<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 14-06-18
 * Time: 14:16
 */

namespace edwrodrig\genbank_reader\exception;

use Exception;

class OpenFileException extends Exception
{

    /**
     * OpenFileException constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        parent::__construct($filename);
    }
}