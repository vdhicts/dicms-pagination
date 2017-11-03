<?php

namespace Vdhicts\Dicms\Pagination\Exceptions;

use Exception;
use Vdhicts\Dicms\Pagination\PaginationException;

class InvalidIntegerType extends PaginationException
{
    /**
     * InvalidIntegerType constructor.
     * @param string $givenType
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($givenType, $code = 0, Exception $previous = null)
    {
        parent::__construct(
            sprintf('The field requires an integer, but a `%s` is provided', $givenType),
            $code,
            $previous
        );
    }
}
