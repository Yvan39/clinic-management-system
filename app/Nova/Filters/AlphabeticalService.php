<?php
namespace App\Nova\Filters;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ServiceAlphabetical extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = 'Sort by Name';
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->orderBy('name', 'asc');
    }
    /**
     * Get the options for the filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Alphabetical' => 'asc',
        ];
    }
}