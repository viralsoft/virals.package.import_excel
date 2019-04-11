<?php
/**
 * Created by PhpStorm.
 * User: hm
 * Date: 11/04/2019
 * Time: 10:38
 */

namespace ViralsBackpack\BackPackExcel\Http\Controllers;


use Illuminate\Http\Request;
use ViralsBackpack\BackPackExcel\Models\ExcelField;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class ViralsExcelFieldCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(ExcelField::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/virals-excel-field');
        $this->crud->setEntityNameStrings('excel field', 'excel field');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('create');
        $this->crud->addButtonFromView('line', 'vivals_excel_show_data_field', 'vivals_excel_show_data_field', 'beginning');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name' => 'id', // The db column names
            'label' => "Id", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'model_type', // The db column name
            'label' => "Model type", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'request_type', // The db column name
            'label' => "Request type", // Table column heading
            'type' => 'text'
        ]);
    }

    public function store(Request $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(Request $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function getData($id)
    {
        $entry = $this->crud->getEntry($id);
        if ($entry) {
            return response()->json(json_decode($entry->data),200,[],JSON_PRETTY_PRINT);
        }
        return response()->json([]);
    }
}