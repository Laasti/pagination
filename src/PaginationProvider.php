<?php

namespace Laasti\Pagination;

use League\Container\ServiceProvider;

/**
 * PaginationProvider Class
 *
 */
class PaginationProvider extends ServiceProvider
{

    protected $provides = [
        'Laasti\Pagination\Pagination',
        'Laasti\Pagination\PaginationFactoryTrait',
        'Laasti\Pagination\PaginationFactoryInterface',
    ];

    protected $defaultConfig = [
        'per_page' => 10,
        'base_url' => '',
        'neighbours' => 3
    ];

    public function register()
    {
        $di = $this->getContainer();

        if (isset($di['config.pagination']) && is_array($di['config.pagination'])) {
            $config = array_merge($this->defaultConfig, $di['config.pagination']);
        } else {
            $config = $this->defaultConfig;
        }

        $di->add('Laasti\Pagination\Pagination',
            function ($currentPage, $total, $perPage = null, $baseUrl = null, $neighbours = null) use ($config) {
                $perPage = $perPage ?: $config['per_page'];
                $baseUrl = $baseUrl ?: $config['base_url'];
                $neighbours = $neighbours ?: $config['neighbours'];
                return new Pagination($currentPage, $total, $perPage, $baseUrl, $neighbours);
            });
    }
}
