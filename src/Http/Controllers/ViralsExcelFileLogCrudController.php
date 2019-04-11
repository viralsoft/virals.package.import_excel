<?php

namespace ViralsBackpack\BackPackExcel\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\CrudPanel;
use ViralsBackpack\BackPackExcel\Models\ExcelFileLog;

/**
 * Class PostCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ViralsExcelFileLogCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(ExcelFileLog::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/virals-excel-file-log');
        $this->crud->setEntityNameStrings('excel file log', 'excel file log');
        if (session()->has('file_excel_id')) {
            $this->crud->addClause('whereFileId', session()->get('file_excel_id'));
        }
        $this->crud->removeAllButtons();
        $this->crud->addButtonFromView('line', 'vivals_excel_show_log', 'vivals_excel_show_log', 'beginning');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name' => 'row', // The db column name
            'label' => "Row", // Table column heading
            'type' => 'text'
        ]);
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
