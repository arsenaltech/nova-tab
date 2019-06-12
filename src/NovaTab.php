<?php

namespace Arsenaltech\NovaTab;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\ResourceTool;
use Illuminate\Http\Resources\MergeValue;

class NovaTab extends MergeValue implements \JsonSerializable
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-tab';

    /**
     * The name of the panel.
     *
     * @var string
     */
    public $name;

    /**
     * The panel fields.
     *
     * @var array
     */
    public $data;

    /**
     * The callback for determine if the tab should show a warning on created.
     *
     * @var array
     */
    public $hasErrorCallback;

    public $panel;

    /**
     * Create a new panel instance.
     *
     * @param  string  $name
     * @param  \Closure|array  $fields
     * @param  string  $html
     * @return void
     */
    public function __construct($name, $fields = [], $html = null, $errorCallback = null)
    {
        $this->name = $name;
        $this->hasErrorCallback = $errorCallback;

        parent::__construct($this->prepareFields($fields, $html));
    }

    /**
     * Prepare the panel for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'component' => 'nova-tab',
            'name' => $this->name,
            'prefixComponent' => true,
            'panel' => $this->panel,
            'indexName' => $this->name
        ];
    }

    /**
     * Prepare the given fields.
     *
     * @param  \Closure|array  $fields
     * @return array
     */
    protected function prepareFields($fields, $html)
    {
        $hasError = $this->hasErrorCallback ? call_user_func($this->hasErrorCallback) : false;

        return collect(is_callable($fields) ? $fields() : $fields)
            ->each(function ($field) use ($html, $hasError) {
                if ($field instanceof Field || $field instanceof ResourceTool) {
                    $field->withMeta([
                        'tab' => $this->name,
                        'tabHTML' => $html,
                        'hasError' => $hasError,
                    ]);
                }
            })->all();
    }
}
