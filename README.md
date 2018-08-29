# Laravel Nova Tab

Custom Nova field to render tabs


![Detail](https://raw.githubusercontent.com/chaseconey/nova-external-image/master/screenshots/index-ss.png)
![Edit](https://raw.githubusercontent.com/chaseconey/nova-external-image/master/screenshots/detail-ss.png)

## Installation

Install the package into a Laravel app that uses [Nova](https://nova.laravel.com) with Composer:

```bash
composer require arsenaltech/nova-tab
```

## Usage

Extend App\Nova\Resource from Arsenaltech\NovaTab\Resource

```php
use Arsenaltech\NovaTab\Resource as TabResource;

abstract class Resource extends TabResource
```

Add the field to your resource in the `fields` method:

```php
use Arsenaltech\NovaTab\NovaTab;

new NovaTab('Address Information', $this->addressFields()),
new NovaTab('Other Information', $this->otherFields()),

```


