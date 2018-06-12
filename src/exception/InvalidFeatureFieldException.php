<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 12-06-18
 * Time: 16:33
 */

namespace edwrodrig\genbank\exception;

use Exception;

class InvalidFeatureFieldException extends Exception
{

    /**
     * InvalidFeatureFieldException constructor.
     * @param bool|string $line
     */
    public function __construct(string $line)
    {
        parent::__construct($line);
    }
}