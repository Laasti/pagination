<?php

namespace Laasti\Pagination;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use LogicException;
use OutOfBoundsException;

/**
 * Pagination Class
 *
 */
class Pagination implements IteratorAggregate, Countable
{

    protected $currentPage;
    protected $total;
    protected $perPage;
    protected $baseUrl;
    protected $neighbours;
    protected $formatter;

    function __construct($currentPage, $total, $perPage = 10, $baseUrl = '', $neighbours = 3)
    {
        $this->setTotal($total);
        $this->setPerPage($perPage);
        $this->setCurrentPage($currentPage);
        $this->setBaseUrl($baseUrl);
        $this->setNeighbours($neighbours);
    }

    public function current()
    {
        return $this->createPage($this->currentPage);
    }

    public function first()
    {
        return $this->currentPage === 1 ? null : $this->createPage(1);
    }

    public function last()
    {
        $last = $this->getTotalPages();
        return $this->currentPage === $last ? null : $this->createPage($last);
    }

    public function previous()
    {
        return $this->currentPage === 1 ? null : $this->createPage($this->currentPage - 1);
    }

    public function next()
    {
        return $this->currentPage === $this->getTotalPages() || $this->getTotalPages() === 0 ? null : $this->createPage($this->currentPage + 1);
    }

    public function build()
    {
        if ($this->total === 0) {
            return [];
        }

        //Previous pages
        $first = $this->getStartPage();
        $last = $this->getEndPage();
        $pages = [];

        for ($i = $first; $i <= $last; $i++) {
            $pages[] = $this->createPage($i);
        }

        return $pages;
    }

    public function count()
    {
        return $this->getEndPage() - $this->getStartPage() + 1;
    }

    public function getOffset()
    {
        return $this->currentPage * $this->perPage - $this->perPage;
    }

    public function getLimit()
    {
        return $this->perPage;
    }

    public function getTotalPages()
    {
        return (int) ceil($this->total / $this->perPage);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->build());
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function getPerPage()
    {
        return $this->perPage;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function getNeighbours()
    {
        return $this->neighbours;
    }

    public function setCurrentPage($currentPage)
    {
        if ($this->total > 0 && ($currentPage <= 0 || $currentPage > $this->getTotalPages())) {
            throw new OutOfBoundsException('Items per page must be at least 1');
        }
        $this->currentPage = $currentPage;
        return $this;
    }

    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    public function setPerPage($perPage)
    {
        if ($perPage <= 0) {
            throw new LogicException('Items per page must be at least 1');
        }
        $this->perPage = $perPage;
        return $this;
    }

    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/').'/';
        return $this;
    }

    public function setNeighbours($neighbours)
    {
        if ($neighbours <= 0) {
            throw new LogicException('Number of neighboring pages must be at least 1');
        }
        $this->neighbours = $neighbours;
        return $this;
    }

    public function getFormatter()
    {

        if (is_null($this->formatter)) {
            throw new \RuntimeException('You need to set a formatter.');
        }

        return $this->formatter;
    }

    public function setFormatter(Formatters\FormatterInterface $formatter)
    {
        $this->formatter = $formatter;

        return $this;
    }

    public function render()
    {
        return $this->getFormatter()->render($this);
    }

    protected function createPage($number)
    {
        return new Page($number, $number === $this->currentPage, $this->baseUrl);
    }

    protected function getStartPage()
    {
        return max($this->currentPage - $this->neighbours, 1);
    }

    protected function getEndPage()
    {
        return min($this->currentPage + $this->neighbours, $this->getTotalPages());
    }

    public function __toString()
    {
        try {
            return $this->render();
        } catch (\Exception $e) {
            return 'No formatter.';
        }
    }

}
