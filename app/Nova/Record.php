<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Record extends Resource
{
    public static $group = '1Patient Management';

    public static function availableForNavigation(Request $request)
    {
        return false;
    }

    public static function label () {
        return "Health Backgrounds";
    }
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Record::class;

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
        'medical_history',
        'smoker',
        'allergies',
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

            Textarea::make('Medical History')
                ->rules('required')
                ->showOnIndex()
                ->alwaysShow(),
            Textarea::make('Allergies')
                ->rules('required')
                ->showOnIndex()
                ->alwaysShow(),
            Select::make('Smoker')
                ->options([
                    'Yes' => 'Yes',
                    'No' => 'No',
                ])
                ->default('No') 
                ->displayUsingLabels()
                ->showOnIndex(),
            Textarea::make('Notes')
                ->showOnIndex()
                ->alwaysShow(),
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
        return [];
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