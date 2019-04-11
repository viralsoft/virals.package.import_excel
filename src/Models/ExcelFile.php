<?php

namespace ViralsBackpack\BackPackExcel\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;


class ExcelFile extends Model
{
    use CrudTrait;

    const PROCESSING = 1; // dang xu ly
    const PROCESSED = 2; // da xu ly
    const ERROR = 3;    // gap loi

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'excel_files';

    protected $fillable = [
        'name',
        'url',
        'status',
    ];

    protected $primaryKey = 'id';
    public $timestamps = true;


}
