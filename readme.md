# BackPackExcel

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Description
This package support import data from excel with relationship

## Installation

Via Composer

``` bash
$ composer require viralsbackpack/backpackexcel
```
Run command:
```bash
php artisan vendor:publish --provider="ViralsBackpack\BackPackExcel\BackPackExcelServiceProvider"

php artisan migrate

php artisan storage:link
```

## Setup
_Add trait ```ViralsBackpack\BackPackExcel\Traits\ViralsRelationshipMethod``` to model class, Eg:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use ViralsBackpack\BackPackExcel\Traits\ViralsRelationshipMethod;// <------------------------------- this one

class Tag extends Model
{
    use CrudTrait;
    use ViralsRelationshipMethod; // <------------------------------- this one

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'tags';
    protected $fillable = ['name'];
}
```
_Add attribute $requestExcel to model class if you want validate data import

```php
<?php
use App\Http\Requests\TagRequest;

class Tag extends Model
{
    public $requestExcel = TagRequest::class;
}

```

_Add sidebar manager log import excel
```php
<li><a href="{{ route('excel-fields.index') }}"><i class="fa fa-files-o"></i> <span>Virals Excels</span></a></li>
```

### Export demo excel:
1. Setting file excel demo
![alt text](https://raw.githubusercontent.com/viralsoft/virals.package.import_excel/master/export.png)

Eg:
if you choice 'name' column in relation, you must fill value of 'name' in main sheet model. 

3. Download 

### Import excel
1. You can add the field upload of laravel backpack to controller
```php
$this->crud->addField([   // Upload
    'name' => 'file',
    'label' => 'File',
    'type' => 'upload',
    'upload' => true,
]);
```
2. In store method in controller, you call method import excel
```php
$ip = new ViralsLaravel\ImportRelationExcel\HandlExcel\Import();
$ip->processImport($request->file);
```
## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [author name][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/viralsbackpack/backpackexcel.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/viralsbackpack/backpackexcel.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/viralsbackpack/backpackexcel/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/viralsbackpack/backpackexcel
[link-downloads]: https://packagist.org/packages/viralsbackpack/backpackexcel
[link-travis]: https://travis-ci.org/viralsbackpack/backpackexcel
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/viralsbackpack
[link-contributors]: ../../contributors
