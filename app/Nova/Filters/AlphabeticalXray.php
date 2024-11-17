<?php
namespace App\Nova\Filters;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class XrayPatient extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = 'Name Filter';
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
    // Use join to order by patient name and filter xrays where the patient is of type 'Patient'
    return $query->whereHas('patient', function ($q) {
             $q->where('type', 'Patient');
         })
         ->join('users', 'xrays.patient_id', '=', 'users.id')
         ->orderBy('users.name', 'asc')
         ->select('xrays.*'); // Ensure only xrays columns are selected to avoid conflicts
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
            'Alphabetical' => 'Patient',
        ];
    }
}