<?php

namespace Arsenaltech\NovaTab;

use Illuminate\Support\Collection;
use Laravel\Nova\Panel;
use Laravel\Nova\Resource as NovaResource;
use Laravel\Nova\Http\Requests\NovaRequest;

trait Tabs
{
    public function updateFields(NovaRequest $request) {
        $updateFields = parent::updateFields($request);
        if(!$request->isMethod('get')) {
            return $updateFields;
        }
        $updateFields = $this->availableTabs($request, $updateFields);
        return $updateFields;
    }

    /**
     * Prepare the resource for JSON serialization.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function serializeForDetail(NovaRequest $request)
    {
        $detailFields = parent::serializeForDetail($request);
        $detailFields['fields'] = $this->availableTabs($request, $detailFields['fields']);
        return $detailFields;
    }

    /**
     * Resolve the creation fields.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Support\Collection
     */
    public function creationFields(NovaRequest $request)
    {
        $creationFields = parent::creationFields($request);
        if(!$request->isMethod('get')) {
            return $creationFields;
        }
        return $this->availableTabs($request, $creationFields);
    }

    /**
     * Get the panels that are available for the given request.
     *
     * @param  \Laravel\Nova\Http\Requests\ResourceDetailRequest  $request
     * @return \Illuminate\Support\Collection
     */
    public function availableTabs(NovaRequest $request, $fields)
    {

        $tabs = collect(array_values($this->fields($request)))
            ->whereInstanceOf(NovaTab::class)->values();
        if(count($tabs) > 0) {
            if($fields instanceof Collection) {
                $fields = $fields->all();
            }
            $this->assignFieldsToTabs($request, $fields);
            return collect([
                (NovaTabs::make('tabs'))
                    ->withMeta(['fields'=> array_values($fields)])
            ]);
        }
        return $fields;


    }


    protected function assignFieldsToTabs(NovaRequest $request, $fields)
    {
        foreach ($fields as $field) {
            $name = $field->meta['tab'] ?? Panel::defaultNameFor($request->newResource());
            $field->meta['tab'] = [
                'name'=> $name,
                'html' => $field->meta['tabHTML'] ?? $name
            ];
        }

        return $fields;
    }
}
