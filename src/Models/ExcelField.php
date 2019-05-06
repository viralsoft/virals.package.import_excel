<?php

namespace ViralsLaravel\ImportRelationExcel\Models;

use Illuminate\Database\Eloquent\Model;


class ExcelField extends Model
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'excel_fields';

    protected $fillable = [
        'request_type',
        'name',
        'model_type',
        'data',
        'request_type'
    ];

    protected $primaryKey = 'id';
    public $timestamps = true;

    /**
     * Returns the action column html for datatables.
     * @param $entry
     * @return array|string
     * @throws \Throwable
     */
    public static function laratablesCustomAction($entry)
    {
        $entry->routeName = "excel-fields";
        return view('viralslaravelexcel::contents.btn_stack', [
            'stack_line' => ['download_demo', 'show', 'delete'],
            'stack_top' => ['edit'],
            'entry' => $entry
        ])->render();
    }
}
