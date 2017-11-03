<?php

namespace Vdhicts\Dicms\Pagination;

class Pagination implements Contracts\Paginator
{
    /**
     * Holds the limit.
     * @var int
     */
    private $limit = self::NO_LIMIT;

    /**
     * Holds the offset.
     * @var int
     */
    private $offset = 0;

    /**
     * Holds the total amount of items.
     * @var int
     */
    private $totalItems = 0;

    /**
     * Holds the current page.
     * @var int
     */
    private $page = 1;

    /**
     * Holds extra parameters for the pagination links.
     * @var array
     */
    private $parameters = [];

    /**
     * Holds the total amount of pages.
     * @var int
     */
    private $totalPages = 1;

    /**
     * Holds the number of the first item on the page.
     * @var int
     */
    private $firstItemOnPage = 0;

    /**
     * Holds the number of the last item on the page.
     * @var int
     */
    private $lastItemOnPage = 0;

    /**
     * Pagination constructor.
     * @param int $limit
     * @param int $page
     * @param int $totalItems
     * @param array $parameters
     */
    public function __construct($limit = self::NO_LIMIT, $page = 1, $totalItems = 0, array $parameters = [])
    {
        $this->setLimit($limit);
        $this->setTotalItems($totalItems);
        $this->setPage($page);
        $this->setParameters($parameters);
    }

    /**
     * Returns the limit.
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Determines if a limit is provided.
     * @return bool
     */
    public function hasLimit()
    {
        return $this->getLimit() !== self::NO_LIMIT;
    }

    /**
     * Stores the limit.
     * @param int $limit
     * @throws Exceptions\InvalidIntegerType
     * @throws Exceptions\PositiveIntegerRequired
     */
    private function setLimit($limit = self::NO_LIMIT)
    {
        if (! is_int($limit)) {
            throw new Exceptions\InvalidIntegerType(gettype($limit));
        }

        // A limit of 0 or less is useless, fallback to no limit
        if ($limit !== self::NO_LIMIT && $limit <= 0) {
            throw new Exceptions\PositiveIntegerRequired($limit);
        }

        $this->limit = $limit;
    }

    /**
     * Returns the offset.
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Stores the offset.
     * @param int $offset
     * @throws Exceptions\InvalidIntegerType
     * @throws Exceptions\PositiveIntegerRequired
     */
    private function setOffset($offset)
    {
        $this->offset = $offset;
    }

    /**
     * Returns the total amount of items.
     * @return int
     */
    public function getTotalItems()
    {
        return $this->totalItems;
    }

    /**
     * Stores the total amount of items.
     * @param int $totalItems
     * @throws Exceptions\InvalidIntegerType
     * @throws Exceptions\PositiveIntegerRequired
     */
    private function setTotalItems($totalItems)
    {
        if (! is_int($totalItems)) {
            throw new Exceptions\InvalidIntegerType(gettype($totalItems));
        }

        // A result may have zero or more items
        if ($totalItems < 0) {
            throw new Exceptions\PositiveIntegerRequired($totalItems);
        }

        $this->totalItems = $totalItems;

        $this->changedTotalItems();
    }

    /**
     * The total amount of items is changed, so calculate the total amount of pages also.
     */
    private function changedTotalItems()
    {
        $this->calculateTotalPages();
    }

    /**
     * Calculates the total amount of pages.
     */
    private function calculateTotalPages()
    {
        // With zero items, there is still one page
        if ($this->getTotalItems() === 0) {
            $this->setTotalPages(1);
            return;
        }

        // The total amount of pages is calculated by dividing the total amount of items by the items per page
        $totalPages = ceil($this->getTotalItems() / $this->getLimit());

        $this->setTotalPages((int)$totalPages);
    }

    /**
     * Returns the current page.
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Stores the current page.
     * @param int $page
     * @throws Exceptions\InvalidIntegerType
     * @throws Exceptions\PositiveIntegerRequired
     */
    private function setPage($page)
    {
        if (! is_int($page)) {
            throw new Exceptions\InvalidIntegerType(gettype($page));
        }

        // There is at least one page
        if ($page <= 0) {
            throw new Exceptions\PositiveIntegerRequired($page);
        }

        $this->page = $page;

        $this->changedPage();
    }

    /**
     * When the page is changed, the offset and item numbers should be recalculated.
     */
    private function changedPage()
    {
        $this->calculateOffset();
        $this->calculateFirstItemOnPage();
        $this->calculateLastItemOnPage();
    }

    /**
     * Calculates the offset for the current page and limit.
     */
    private function calculateOffset()
    {
        // When no limit is provided, all items are on one page, so the offset is unreasonable
        if (! $this->hasLimit()) {
            $this->setOffset(0);
            return;
        }

        $offset = ($this->getPage() * $this->getLimit()) - $this->getLimit();

        $this->setOffset((int)$offset);
    }

    /**
     * Calculates which item number is the first on the current page.
     */
    private function calculateFirstItemOnPage()
    {
        // Determine the first item on the page
        $firstItem = $this->getOffset() + ($this->getOffset() !== 0 ? 1 : 0);

        $this->setFirstItemOnPage((int)$firstItem);
    }

    /**
     * Calculates which item number is the last on the current page.
     */
    private function calculateLastItemOnPage()
    {
        // When no limit, the last item is the total amount of items
        if (! $this->hasLimit()) {
            $this->setLastItemOnPage($this->getTotalItems());
            return;
        }

        // Determine the last item on the page
        $lastItem = $this->getOffset() + $this->getLimit();
        if ($this->getTotalItems() !== 0 && $lastItem > $this->getTotalItems()) {
            $lastItem = $this->getTotalItems();
        }

        $this->setLastItemOnPage((int)$lastItem);
    }

    /**
     * Returns the parameters.
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Stores the parameters.
     * @param array $parameters
     */
    private function setParameters(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * Returns the total amount of pages.
     * @return int
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * Stores the total amount of pages.
     * @param int $totalPages
     * @throws Exceptions\InvalidIntegerType
     * @throws Exceptions\PositiveIntegerRequired
     */
    private function setTotalPages($totalPages = 1)
    {
        $this->totalPages = $totalPages;
    }

    /**
     * Returns the number of the first item on the page.
     * @return int
     */
    public function getFirstItemOnPage()
    {
        return $this->firstItemOnPage;
    }

    /**
     * Stores the number of the first item on the page.
     * @param int $firstItemOnPage
     * @throws Exceptions\InvalidIntegerType
     * @throws Exceptions\PositiveIntegerRequired
     */
    private function setFirstItemOnPage($firstItemOnPage)
    {
        $this->firstItemOnPage = $firstItemOnPage;
    }

    /**
     * Returns the number of the last item on the page.
     * @return int
     */
    public function getLastItemOnPage()
    {
        return $this->lastItemOnPage;
    }

    /**
     * Stores the number of the last item on the page.
     * @param int $lastItemOnPage
     * @throws Exceptions\InvalidIntegerType
     * @throws Exceptions\PositiveIntegerRequired
     */
    private function setLastItemOnPage($lastItemOnPage)
    {
        $this->lastItemOnPage = $lastItemOnPage;
    }
}
