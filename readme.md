# BackPackExcel

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

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

## Usage
Add trait ```ViralsBackpack\BackPackExcel\Traits\ViralsRelationshipMethod``` to main model import data from excel , Eg:

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
Export demo excel:
1. Add field to controller have model import:
```php
$this->crud->addField([ 
    'name' => 'prices', //key unique field
    'type' => 'virals_template_excel',
    'request_class' => TagRequest::class, // request class validate field in excel
]);
```
2. Setting file excel demo

Add sidebar manager log import excel
```php
<li><a href="{{ backpack_url('virals-excel-field') }}"><i class="fa fa-files-o"></i> <span>Fields</span></a></li>
<li><a href="{{ backpack_url('virals-excel-file') }}"><i class="fa fa-files-o"></i> <span>Excel Files</span></a></li>
<li><a href="{{ backpack_url('virals-excel-file-log') }}"><i class="fa fa-files-o"></i> <span>Logs</span></a></li>
```
![alt text](https://raw.githubusercontent.com/viralsoft/virals.package.import_excel/master/export.png)
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
