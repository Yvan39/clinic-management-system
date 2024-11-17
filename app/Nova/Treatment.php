<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\Treatment as ModelsTreatment;
use App\Models\TreatmentType;
use Laravel\Nova\Fields\Hidden;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use OptimistDigital\MultiselectField\Multiselect;

class Treatment extends Resource
{
    public static function label () {
        return "Treatment History";
    }
    public static $group = '1Patient Management';
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Treatment::class;

    public static function indexQuery(NovaRequest $request, $query)
    {
        // Customize the search logic here
        if ($request->get('search')) {
            return $query->orWhereHas('patient', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->get('search') . '%');
            });
        }

        return $query;
    }
    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'patient.name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'type',
        'description',
        'dosage',
        'notes',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Date::make('Date', 'created_at')
                ->rules('required')
                ->sortable(),
            
            $request->isUpdateOrUpdateAttachedRequest()
            ? Text::make('Patient')
                ->resolveUsing(function () {
                    return $this->patient ? $this->patient->name : 'No patient assigned';
                })
                ->readonly()
                ->sortable()
            : BelongsTo::make('Patient', 'patient', PatientRecord::class)
                ->rules('required')
                ->sortable(), // Keep dropdown on create only

            Multiselect::make('Type')
                ->rules('required')
                ->options(TreatmentType::get()->pluck('name', 'name')),
            Textarea::make('Description')
                ->showOnIndex()
                ->alwaysShow(),
            Hidden::make('medication')->default(fn () => '----'),
            Hidden::make('dosage')->default(fn () => '----'),
            Hidden::make('start_date')->default(fn () => now()),
            Hidden::make('end_date')->default(fn () => now()),
            Hidden::make('doctor')->default(fn () => '---'),
            Hidden::make('outcome')->default(fn () => '---'),
            Hidden::make('notes')->default(fn () => '---'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            \App\Nova\Filters\Date::make(),
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            (new DownloadExcel())
                ->withHeadings(),
        ];
    }
}