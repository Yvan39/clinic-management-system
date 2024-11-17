<?php
namespace App\Nova\Filters;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Carbon\Carbon;
class Slot extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = 'Slot';
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
        // Apply sorting by the start time of each slot, unconditionally
        return $query->orderByRaw('STR_TO_DATE(SUBSTRING_INDEX(slot, " - ", 1), "%h:%i %p")');
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
            'By Time Slot' => 'asc',
        ];
    }
}
