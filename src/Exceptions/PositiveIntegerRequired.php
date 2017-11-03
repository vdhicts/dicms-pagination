<?php

namespace Vdhicts\Dicms\Pagination\Exceptions;

use Throwable;
use Vdhicts\Dicms\Pagination\PaginationException;

class PositiveIntegerRequired extends PaginationException
{
    /**
     * InvalidPositiveInteger constructor.
     * @param int $value
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($value, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('The provided value must be a positive integer, but `%s` is provided', $value),
            $code,
            $previous
        );
    }
}
