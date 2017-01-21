<?php

namespace Laasti\Pagination\Tests;

use Laasti\Pagination\PaginationProvider;
use League\Container\Container;

/**
 * PaginationTest Class
 *
 */
class PaginationProviderTest extends \PHPUnit_Framework_TestCase
{

    public function testProvider()
    {
        $container = new Container();
        $container->addServiceProvider(new PaginationProvider);

        $pagination = $container->get('Laasti\Pagination\Pagination', [1, 90]);
        $this->assertTrue($pagination->getTotalPages() === 9);

        $pagination = $container->get('Laasti\Pagination\Pagination', [5, 150, 15, 'http://acme.com/pages/', 1]);
        $this->assertTrue($pagination->getTotalPages() === 10);
        $this->assertTrue(count($pagination) === 3);
        $this->assertTrue($pagination->next()->link() === 'http://acme.com/pages/6');
    }

    public function testProviderConfig()
    {
        $container = new Container();
        $container->addServiceProvider(new PaginationProvider);
        $container['config.pagination'] = [
            'per_page' => 5,
            'neighbours' => 2,
            'base_url' => 'http://acme.com/pages/'
        ];

        $pagination = $container->get('Laasti\Pagination\Pagination', [1, 50]);
        $this->assertTrue($pagination->getTotalPages() === 10);
        $this->assertTrue(count($pagination) === 3);
        $this->assertTrue($pagination->last()->link() === 'http://acme.com/pages/10');
    }
}
