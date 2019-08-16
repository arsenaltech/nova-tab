<?php

namespace Arsenaltech\NovaTab;

use Laravel\Nova\Fields\Field;

class NovaTabs extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-tabs';

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        if (empty($this->meta()['fields'][0]->panel)) {
            $this->panel = Panel::defaultNameForUpdate(app(NovaRequest::class)->newResource());
        } else {
            $this->panel = $this->meta()['fields'][0]->panel;
        }
        return array_merge([
            'component' => $this->component(),
            'prefixComponent' => true,
            'indexName' => $this->name,
            'name' => $this->name,
            'attribute' => $this->attribute,
            'value' => $this->value,
            'panel' => $this->panel,
            'sortable' => $this->sortable,
            'nullable' => $this->nullable,
            'readonly' => $this->isReadonly(app(NovaRequest::class)),
            'textAlign' => $this->textAlign,
        ], $this->meta()); 
    }   
}
