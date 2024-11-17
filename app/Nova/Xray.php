<?php

namespace App\Nova;

use App\Nova\Filters\XrayPatient;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Xray extends Resource
{
    public static $group = '1Patient Management';

    public static function label () {
        return "X-Rays";
    }

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
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Xray::class;

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
        'date',
        'type',
        'findings',
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
            Date::make('Date')
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

            Hidden::make('Type')->default(fn () => 'dental'),
            // Textarea::make('Radiologist Report')
            //     ->alwaysShow(),
            Textarea::make('Findings', 'findings')
                ->showOnIndex()
                ->alwaysShow(),
            // Textarea::make('Diagnosis')
            //     ->showOnIndex()
            //     ->alwaysShow(),
            // Textarea::make('Follow Up')
            //     ->alwaysShow(),
            Image::make('Image')
                ->rules('required'),
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
            new XrayPatient,
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
        return [];
    }
}