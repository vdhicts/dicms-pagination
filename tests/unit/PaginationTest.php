<?php

use PHPUnit\Framework\TestCase;
use Vdhicts\Dicms\Pagination\Exceptions;
use Vdhicts\Dicms\Pagination\Pagination;

class PaginationTest extends TestCase
{
    public function testExistence()
    {
        $this->assertTrue(class_exists(Pagination::class));
    }

    public function testInstantiation()
    {
        $pagination = new Pagination();
        $this->assertFalse($pagination->hasLimit());
        $this->assertSame(Pagination::NO_LIMIT, $pagination->getLimit());
        $this->assertSame(0, $pagination->getOffset());
        $this->assertSame(0, $pagination->getTotalItems());
        $this->assertSame(1, $pagination->getPage());
        $this->assertSame(1, $pagination->getTotalPages());
        $this->assertSame(0, $pagination->getFirstItemOnPage());
        $this->assertSame(0, $pagination->getLastItemOnPage());
    }

    public function testPaginationFirstPageWithoutTotalItems()
    {
        $limit = 25;
        $page = 2;

        $pagination = new Pagination($limit, $page);
        $this->assertSame($limit, $pagination->getLimit());
        $this->assertTrue($pagination->hasLimit());
        $this->assertSame(25, $pagination->getOffset());
        $this->assertSame(0, $pagination->getTotalItems());
        $this->assertSame($page, $pagination->getPage());
        $this->assertSame(0, count($pagination->getParameters()));
        $this->assertSame(26, $pagination->getFirstItemOnPage());
        $this->assertSame(50, $pagination->getLastItemOnPage());
    }

    public function testPaginationFirstPageWithTotalItems()
    {
        $limit = 25;
        $page = 2;
        $totalItems = 54;

        $pagination = new Pagination($limit, $page, $totalItems);
        $this->assertSame($limit, $pagination->getLimit());
        $this->assertTrue($pagination->hasLimit());
        $this->assertSame(25, $pagination->getOffset());
        $this->assertSame($totalItems, $pagination->getTotalItems());
        $this->assertSame($page, $pagination->getPage());
        $this->assertSame(3, $pagination->getTotalPages());
        $this->assertSame(0, count($pagination->getParameters()));
        $this->assertSame(26, $pagination->getFirstItemOnPage());
        $this->assertSame(50, $pagination->getLastItemOnPage());
    }

    public function testPaginationSecondPageWithoutTotalItems()
    {
        $limit = 25;
        $page = 2;

        $pagination = new Pagination($limit, $page);
        $this->assertSame($limit, $pagination->getLimit());
        $this->assertTrue($pagination->hasLimit());
        $this->assertSame(25, $pagination->getOffset());
        $this->assertSame(0, $pagination->getTotalItems());
        $this->assertSame($page, $pagination->getPage());
        $this->assertSame(0, count($pagination->getParameters()));
        $this->assertSame(26, $pagination->getFirstItemOnPage());
        $this->assertSame(50, $pagination->getLastItemOnPage());
    }

    public function testPaginationSecondPageWithTotalItems()
    {
        $limit = 25;
        $page = 2;
        $totalItems = 54;

        $pagination = new Pagination($limit, $page, $totalItems);
        $this->assertSame($limit, $pagination->getLimit());
        $this->assertTrue($pagination->hasLimit());
        $this->assertSame(25, $pagination->getOffset());
        $this->assertSame($totalItems, $pagination->getTotalItems());
        $this->assertSame($page, $pagination->getPage());
        $this->assertSame(3, $pagination->getTotalPages());
        $this->assertSame(0, count($pagination->getParameters()));
        $this->assertSame(26, $pagination->getFirstItemOnPage());
        $this->assertSame(50, $pagination->getLastItemOnPage());
    }

    public function testPaginationLastPageWithoutTotalItems()
    {
        $limit = 25;
        $page = 3;

        $pagination = new Pagination($limit, $page);
        $this->assertSame($limit, $pagination->getLimit());
        $this->assertTrue($pagination->hasLimit());
        $this->assertSame(50, $pagination->getOffset());
        $this->assertSame(0, $pagination->getTotalItems());
        $this->assertSame($page, $pagination->getPage());
        $this->assertSame(0, count($pagination->getParameters()));
        $this->assertSame(51, $pagination->getFirstItemOnPage());
        $this->assertSame(75, $pagination->getLastItemOnPage()); // total items is unknown, so 75 is the last one
    }

    public function testPaginationLastPageWithTotalItems()
    {
        $limit = 25;
        $page = 3;
        $totalItems = 54;

        $pagination = new Pagination($limit, $page, $totalItems);
        $this->assertSame($limit, $pagination->getLimit());
        $this->assertTrue($pagination->hasLimit());
        $this->assertSame(50, $pagination->getOffset());
        $this->assertSame($totalItems, $pagination->getTotalItems());
        $this->assertSame($page, $pagination->getPage());
        $this->assertSame(3, $pagination->getTotalPages());
        $this->assertSame(0, count($pagination->getParameters()));
        $this->assertSame(51, $pagination->getFirstItemOnPage());
        $this->assertSame(54, $pagination->getLastItemOnPage());
    }

    public function testPaginationWithParameters()
    {
        $limit = 25;
        $page = 3;
        $totalItems = 54;
        $parameters = ['test' => 1];

        $pagination = new Pagination($limit, $page, $totalItems, $parameters);
        $this->assertSame($limit, $pagination->getLimit());
        $this->assertTrue($pagination->hasLimit());
        $this->assertSame(50, $pagination->getOffset());
        $this->assertSame($totalItems, $pagination->getTotalItems());
        $this->assertSame($page, $pagination->getPage());
        $this->assertSame(3, $pagination->getTotalPages());
        $this->assertSame(1, count($pagination->getParameters()));
        $this->assertArrayHasKey('test', $pagination->getParameters());
        $this->assertSame($parameters['test'], $pagination->getParameters()['test']);
        $this->assertSame(51, $pagination->getFirstItemOnPage());
        $this->assertSame(54, $pagination->getLastItemOnPage());
    }

    public function testInvalidLimitType()
    {
        $this->expectException(Exceptions\InvalidIntegerType::class);
        new Pagination('a');
    }

    public function testInvalidLimitValue()
    {
        $this->expectException(Exceptions\PositiveIntegerRequired::class);
        new Pagination(-10);
    }

    public function testInvalidPageType()
    {
        $this->expectException(Exceptions\InvalidIntegerType::class);
        new Pagination(10, 'a');
    }

    public function testInvalidPageValue()
    {
        $this->expectException(Exceptions\PositiveIntegerRequired::class);
        new Pagination(10, -10);
    }

    public function testInvalidTotalItemsType()
    {
        $this->expectException(Exceptions\InvalidIntegerType::class);
        new Pagination(10, 1, 'a');
    }

    public function testInvalidTotalItemsValue()
    {
        $this->expectException(Exceptions\PositiveIntegerRequired::class);
        new Pagination(10, 1, -10);
    }
}
