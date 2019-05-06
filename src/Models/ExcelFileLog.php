<?php

namespace ViralsBackpack\BackPackExcel\Models;

use Illuminate\Database\Eloquent\Model;


class ExcelFileLog extends Model
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'excel_file_logs';

    protected $fillable = [
        'file_id',
        'row',
        'note',
    ];

    protected $primaryKey = 'id';
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    /**
     * Returns the action column html for datatables.
     * @param $entry
     * @return array|string
     * @throws \Throwable
     */
    public static function laratablesCustomAction($entry)
    {
        $entry->routeName = "excel-logs";
        return view('viralslaravelexcel::contents.btn_stack', [
            'stack_line' => ['delete', 'show_log'],
            'entry' => $entry,
        ])->render();
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function file()
    {
        return $this->belongsTo(ExcelFile::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

}
