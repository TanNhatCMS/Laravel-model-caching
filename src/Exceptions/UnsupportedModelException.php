<?php

namespace TanNhatCMS\ModelCaching\Exceptions;

use Exception;

/**
 * Class UnsupportedModelException
 * 
 * Ngoại lệ cho model không hỗ trợ caching.
 * Exception for models that do not support caching.
 */
class UnsupportedModelException extends Exception
{
    /**
     * @param string $message
     */
    public function __construct(string $message = 'Model does not implement Cacheable interface yet.')
    {
        parent::__construct($message);
    }
}
