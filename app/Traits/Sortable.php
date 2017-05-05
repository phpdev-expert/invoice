<?php

namespace FI\Traits;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

trait Sortable
{
    public function scopeSortable($query, $defaultSort = [])
    {
        if (Input::has('s') and Input::has('o') and isset($this->sortable) and is_array($this->sortable) and in_array(Input::get('s'), $this->sortable))
        {
            return $query->orderBy(Input::get('s'), Input::get('o'));
        }

        elseif ($defaultSort)
        {
            foreach ($defaultSort as $col => $sort)
            {
                $query->orderBy($col, $sort);
            }

            return $query;
        }

        return $query;
    }

    public static function link($col, $title = null, $requestMatches = null)
    {
        if ($requestMatches and !Request::is($requestMatches))
        {
            return $title;
        }

        if (is_null($title))
        {
            $title = str_replace('_', ' ', $col);
            $title = ucfirst($title);
        }

        $indicator  = (Input::get('s') == $col ? (Input::get('o') === 'asc' ? '&uarr;' : '&darr;') : null);
        $parameters = array_merge(Input::get(), ['s' => $col, 'o' => (Input::get('o') === 'asc' ? 'desc' : 'asc')]);

        return link_to_route(Route::currentRouteName(), "$title $indicator", $parameters);
    }
}