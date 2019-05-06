<?php

namespace ViralsBackpack\BackPackExcel\Models;

use Illuminate\Database\Eloquent\Model;

class ExcelFile extends Model
{
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

    /**
     * Returns the action column html for datatables.
     * @param $entry
     * @return array|string
     * @throws \Throwable
     */
    public static function laratablesCustomAction($entry)
    {
        $entry->routeName = "excel-files";
        return view('viralslaravelexcel::contents.btn_stack', [
            'stack_line' => ['delete', 'show'],
            'entry' => $entry,
            'label_bt' => "Show log"
        ])->render();
    }
}
