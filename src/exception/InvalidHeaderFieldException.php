<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 11-06-18
 * Time: 16:28
 */

namespace edwrodrig\genbank_reader\exception;

use Exception;

class InvalidHeaderFieldException extends Exception
{

    /**
     * InvalidHeaderFieldException constructor.
     * @param string $field
     */
    public function __construct(string $field)
    {
        parent::__construct($field);
    }
}