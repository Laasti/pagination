<?php


namespace Laasti\Pagination\Tests;

use Laasti\Pagination\PaginationProvider;
use League\Container\Container;

require 'FakePaginationAware.php';
/**
 * PaginationTest Class
 *
 */
class PaginationFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testFactory()
    {
        $paginationAware = new FakePaginationAware();
        $paginationAware->createPagination(5, 100);
    }

    public function testFactoryConfig()
    {
        $paginationAware = new FakePaginationAware();
        $pagination = $paginationAware->createPagination(1, 15, 5, 'http://acme.com/pages/', 1);

        $this->assertTrue(count($pagination) === 2);
        $this->assertTrue($pagination->getTotalPages() === 3);
        $this->assertTrue($pagination->next()->link() === 'http://acme.com/pages/2');
    }

    public function testContainerFactory()
    {
        $container = new Container();
        $container->addServiceProvider(new PaginationProvider);
        $container->add('Laasti\Pagination\Tests\FakePaginationAware')->withMethodCall('setContainer', ['League\Container\ContainerInterface']);

        $paginationAware = $container->get('Laasti\Pagination\Tests\FakePaginationAware');
        $paginationAware->createPagination(1, 100);
    }

    public function testContainerFactoryConfig()
    {
        $container = new Container();
        $container->addServiceProvider(new PaginationProvider);
        $container->add('Laasti\Pagination\Tests\FakePaginationAware')->withMethodCall('setContainer', ['League\Container\ContainerInterface']);

        $paginationAware = $container->get('Laasti\Pagination\Tests\FakePaginationAware');
        $pagination = $paginationAware->createPagination(1, 15, 5, 'http://acme.com/pages/', 1);

        $this->assertTrue(count($pagination) === 2);
        $this->assertTrue($pagination->getTotalPages() === 3);
        $this->assertTrue($pagination->next()->link() === 'http://acme.com/pages/2');
    }


}
