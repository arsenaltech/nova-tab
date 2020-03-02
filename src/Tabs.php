<?php

namespace Arsenaltech\NovaTab;

use Illuminate\Support\Collection;
use Laravel\Nova\Fields\FieldCollection;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

trait Tabs
{
    /**
     * Resolve the update fields.
     *
     * @param NovaRequest $request
     * @return Collection|FieldCollection
     */
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
     * @param NovaRequest $request
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
     * @param NovaRequest $request
     * @return Collection
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
     * @param NovaRequest $request
     * @param $fields
     * @return Collection
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
            return new FieldCollection([
                (NovaTabs::make('tabs'))
                    ->withMeta(['fields'=> array_values($fields)])
            ]);
        }
        return $fields;


    }

    /**
     * Assign fields to tabs
     *
     * @param NovaRequest $request
     * @param $fields
     * @return mixed
     */
    protected function assignFieldsToTabs(NovaRequest $request, $fields)
    {
        foreach ($fields as $field) {
            $name = $field->meta['tab'] ?? Panel::defaultNameForCreate($request->newResource());
            $field->meta['tab'] = [
                'name'=> $name,
                'html' => $field->meta['tabHTML'] ?? $name
            ];
        }

        return $fields;
    }
}
