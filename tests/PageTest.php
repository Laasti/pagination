<?php

namespace Laasti\Pagination\Tests;

use Laasti\Pagination\Page;

/**
 * PaginationTest Class
 *
 */
class PageTest extends \PHPUnit_Framework_TestCase
{

    public function testGetters()
    {
        $page = new Page(2, true, 'http:/acme.com/pages/');

        $this->assertTrue($page->number() === 2);
        $this->assertTrue($page->isActive() === true);
        $this->assertTrue($page->link() === 'http:/acme.com/pages/2');
        //Test __toString
        $this->assertTrue($page . '5' === '25');
    }
}
