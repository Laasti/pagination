<?php

namespace Laasti\Pagination\Formatters;

use Laasti\Pagination\Pagination;

interface FormatterInterface
{
    public function render(Pagination $pagination);
}
