<?php

namespace Laasti\Pagination;

/**
 * Page Class
 *
 */
class Page
{

    /**
     * @var int
     */
    protected $number;

    /**
     * @var boolean
     */
    protected $isActive;

    /**
     * @var string
     */
    protected $baseUrl;


    public function __construct($number, $isActive, $baseUrl)
    {
        $this->number = (int) $number;
        $this->isActive = (bool) $isActive;
        $this->baseUrl = $baseUrl;
    }

    public function number()
    {
        return $this->number;
    }

    public function isActive()
    {
        return $this->isActive;
    }

    public function link()
    {
        return $this->baseUrl.$this->number;
    }

    public function __toString()
    {
        return (string) $this->number();
    }
}
