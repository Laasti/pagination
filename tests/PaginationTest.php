<?php

namespace Laasti\Pagination\Tests;

use Laasti\Pagination\Formatters\PlainHtml;
use Laasti\Pagination\Pagination;

/**
 * PaginationTest Class
 *
 */
class PaginationTest extends \PHPUnit_Framework_TestCase
{

    public function testGetters()
    {
        $pagination = new Pagination(2, 50, 10, 'http:/acme.com/pages/', 4);
        $this->assertTrue($pagination->getOffset() === 10);
        $this->assertTrue($pagination->getLimit() === 10);
        $this->assertTrue($pagination->getCurrentPage() === 2);
        $this->assertTrue($pagination->getTotal() === 50);
        $this->assertTrue($pagination->getTotalPages() === 5);
        $this->assertTrue($pagination->getPerPage() === 10);
        $this->assertTrue($pagination->getBaseUrl() === 'http:/acme.com/pages/');
        $this->assertTrue($pagination->getNeighbours() === 4);
    }

    public function testBaseUrlSlash()
    {
        $pagination = new Pagination(2, 50, 10, 'http:/acme.com/pages', 4);
        $this->assertTrue($pagination->getBaseUrl() === 'http:/acme.com/pages/');
    }

    public function testOutOfBoundsCurrentPage()
    {
        $pagination = new Pagination(2, 50, 10, 'http:/acme.com/pages/', 4);
        $this->setExpectedException('OutOfBoundsException');
        $pagination->setCurrentPage(6);
    }

    public function testNegativeNeigbours()
    {
        $pagination = new Pagination(2, 50, 10, 'http:/acme.com/pages/', 4);
        $this->setExpectedException('LogicException');
        $pagination->setNeighbours(0);
    }

    public function testNegativePerPage()
    {
        $pagination = new Pagination(2, 50, 10, 'http:/acme.com/pages/', 4);
        $this->setExpectedException('LogicException');
        $pagination->setPerPage(0);
    }

    public function testSpecialLinks()
    {
        $pagination = new Pagination(6, 100, 10, 'http:/acme.com/pages/', 5);
        $this->assertTrue($pagination->next()->number() === 7);
        $this->assertTrue($pagination->previous()->number() === 5);
        $this->assertTrue($pagination->current()->number() === 6);
        $this->assertTrue($pagination->last()->number() === 10);
        $this->assertTrue($pagination->first()->number() === 1);
        $pagination->setCurrentPage(1);
        $this->assertTrue($pagination->previous() === null);
        $this->assertTrue($pagination->first() === null);
        $pagination->setCurrentPage(10);
        $this->assertTrue($pagination->next() === null);
        $this->assertTrue($pagination->last() === null);
    }

    public function testIterator()
    {
        $pagination = new Pagination(6, 100, 10, 'http:/acme.com/pages/', 2);

        $pages = [];
        foreach ($pagination as $page) {
            $pages[] = $page->number();
        }
        $this->assertTrue(implode('', $pages) === '45678');

        $pagination->setCurrentPage(9);
        $pages = [];
        foreach ($pagination as $page) {
            $pages[] = $page->number();
        }
        $this->assertTrue(implode('', $pages) === '78910');

        $pagination->setCurrentPage(1);
        $pages = [];
        foreach ($pagination as $page) {
            $pages[] = $page->number();
        }
        $this->assertTrue(implode('', $pages) === '123');
    }

    public function testCount()
    {
        $pagination = new Pagination(1, 100, 10, 'http:/acme.com/pages/', 2);

        for ($i = 1; $i <= 10; $i++) {
            $pagination->setCurrentPage($i);
            switch ($i) {
                case 1:
                case 10:
                    $this->assertTrue(count($pagination) === 3);
                    break;
                case 2:
                case 9:
                    $this->assertTrue(count($pagination) === 4);
                    break;
                default:
                    $this->assertTrue(count($pagination) === 5);
                    break;
            }
        }

        $pagination->setCurrentPage(1);
        $pagination->setTotal(5);
        $this->assertTrue(count($pagination) === 1);
        $pagination->setTotal(0);
        $this->assertTrue(count($pagination) === 0);

    }

    public function testEmptyPagination()
    {
        $pagination = new Pagination(1, 0, 10, 'http:/acme.com/pages/', 2);
        $this->assertTrue(count($pagination) === 0);
        $this->assertTrue($pagination->getTotalPages() === 0);

        $pages = '';
        foreach ($pagination as $page) {
            $pages .= $page;
        }
        $this->assertTrue($pages === '');
    }

    public function testOnePagePagination()
    {
        $pagination = new Pagination(1, 5, 10, 'http:/acme.com/pages/', 2);
        $this->assertTrue(count($pagination) === 1);
        $this->assertTrue($pagination->getTotalPages() === 1);
        $this->assertTrue($pagination->next() === null);
        $pages = '';
        foreach ($pagination as $page) {
            $pages .= $page->number();
        }
        $this->assertTrue($pages === '1');
    }

    public function testNoFormatter()
    {
        $this->setExpectedException('RuntimeException');
        $pagination = new Pagination(1,20);
        $pagination->getFormatter();
    }

    public function testFormatter()
    {
        $pagination = new Pagination(3, 30, 5);
        $pagination->setFormatter(new PlainHtml());
        $result = '<nav class="Breadcrumb"><a href="/1">First</a><a href="/2">Previous</a><a href="/1">1</a><a href="/2">2</a><b>3</b><a href="/4">4</a><a href="/5">5</a><a href="/6">6</a><a href="/4">Next</a><a href="/6">Last</a></nav>';
        $this->assertEquals($result, (string)$pagination);
    }

}
