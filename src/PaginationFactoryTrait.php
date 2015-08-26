<?php

namespace Laasti\Pagination;

trait PaginationFactoryTrait
{

    /**
     * Create a new pagination object
     * 
     * @param int $currentPage
     * @param int $total
     * @param int $perPage
     * @param string $baseUrl
     * @param int $neighbours
     * @return Pagination
     */
    public function createPagination($currentPage, $total, $perPage = null, $baseUrl = null, $neighbours = null)
    {
        if ($this->container instanceof \League\Container\ContainerInterface) {
            return $this->getContainer()->get('Laasti\Pagination\Pagination', [$currentPage, $total, $perPage, $baseUrl, $neighbours]);
        }
        
        $perPage = $perPage ?: 10;
        $baseUrl = $baseUrl ?: '';
        $neighbours = $neighbours ?: 3;

        return new Pagination($currentPage, $total, $perPage, $baseUrl, $neighbours);
    }

}
