# Laravel Nova Tab

Custom Nova field to render tabs

## Installation

Install the package into a Laravel app that uses [Nova](https://nova.laravel.com) with Composer:

```bash
composer require 64robots/nova-tab
```

## Usage

Add the Tabs trait to your App\Nova\Resource class.

```php
use R64\NovaTab\Tabs;

abstract class Resource extends NovaResource
{
    use Tabs;
```

Add the field to your resource in the `fields` method:

```php
use R64\NovaTab\NovaTab;


new NovaTab('User Information', [
            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:255')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}')]),
new NovaTab('Address Information', $this->addressFields()),
new NovaTab('Other Information', $this->otherFields()),


```
