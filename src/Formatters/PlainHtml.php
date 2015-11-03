<?php

namespace Laasti\Pagination\Formatters;

class PlainHtml implements FormatterInterface
{
    public function render(\Laasti\Pagination\Pagination $pagination)
    {
        $html = '<nav class="Breadcrumb">';
        if (!is_null($pagination->first())) {
            $html .= '<a href="'.$pagination->first()->link().'">First</a>';
        }
        if (!is_null($pagination->previous())) {
            $html .= '<a href="'.$pagination->previous()->link().'">Previous</a>';
        }

        foreach ($pagination as $page) {
            if ($page->isActive()) {
                $html .= '<b>'.$page->number().'</b>';
            } else {
                $html .= '<a href="'.$page->link().'">'.$page->number().'</a>';
            }
        }

        if (!is_null($pagination->next())) {
            $html .= '<a href="'.$pagination->next()->link().'">Next</a>';
        }
        if (!is_null($pagination->last())) {
            $html .= '<a href="'.$pagination->last()->link().'">Last</a>';
        }

        $html .= '</nav>';
        return $html;
    }
}
