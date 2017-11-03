<?php

namespace Vdhicts\Dicms\Pagination\Contracts;

interface Paginator
{
    /**
     * Holds the value for not using a limit.
     */
    const NO_LIMIT = -1;

    /**
     * Returns the maximum amount of records. Returns the NO_LIMIT constant when no limit is provided.
     * @return int
     */
    public function getLimit();

    /**
     * Determines if a maximum amount of records should be used (true) or all records should be used (false).
     * @return bool
     */
    public function hasLimit();

    /**
     * Returns the first record to start with.
     * @return int
     */
    public function getOffset();
}
