<?php

namespace ViralsBackpack\BackPackExcel\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;


class ExcelField extends Model
{
    use CrudTrait;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'excel_fields';

    protected $fillable = [
        'request_type',
        'model_type',
        'data',
        'request_type'
    ];

    protected $primaryKey = 'id';
    public $timestamps = true;


}
