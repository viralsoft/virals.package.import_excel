<?php

namespace ViralsBackpack\BackPackExcel\Http\Controllers;

use Illuminate\Http\Request;
use ViralsBackpack\BackPackExcel\Models\ExcelFileLog;
use Freshbitsweb\Laratables\Laratables;

class ExcelLogController extends BaseExcelController
{
    public function __construct()
    {
        $this->model = ExcelFileLog::class;
        $this->crud['route_name'] = 'excel-logs';
        $this->crud['columns'] = [
            ['name' => 'id'],
            ['name' => 'row'],
            ['name' => 'note', "visible" => false],
            ['name' => 'action', 'orderable' => false, 'searchable' => false]
        ];
        $this->crud['headers'] = [
            'Id',
            'Row',
            'Note',
            'Action'
        ];
        view()->share('crud', $this->crud);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function process(Request $request)
    {
        if (session()->has('file_excel_id')) {
            return Laratables::recordsOf($this->model, function($query) {
                return $query->where('file_id', session()->get('file_excel_id'));
            });
        }
        return [];
    }
}
