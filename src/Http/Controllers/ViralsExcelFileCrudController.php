<?php

namespace ViralsBackpack\BackPackExcel\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\CrudPanel;
use ViralsBackpack\BackPackExcel\Models\ExcelFile;
use Illuminate\Http\Request;
use ViralsBackpack\BackPackExcel\HandlExcel\Export;
use ViralsBackpack\BackPackExcel\Models\ExcelField;

/**
 * Class PostCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ViralsExcelFileCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(ExcelFile::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/virals-excel-file');
        $this->crud->setEntityNameStrings('excel file', 'excel file');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name' => 'name',
            'label' => 'Name',
            'type' => 'closure',
            'function' => function ($entry) {
                return "<a href='" . url($this->crud->route . '/' . $entry->getKey()) . "/show-log" . "'>{$entry->name}</a>";
            },
            'searchLogic' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => "created_at", // The db column name
            'label' => "Create time", // Table column heading
            'type' => "date",
            'format' => 'DD-MMMM-YYYY  HH:mm:ss', // use something else than the base.default_date_format config value
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

    public function showLog($id)
    {
        session()->put('file_excel_id', $id);
        return redirect()->route('crud.virals-excel-file-log.index');
    }

    public function getRelatonColumn(Request $request)
    {
        if ($request->ajax()) {
            $resultValidate = $this->validateRequest($request);
            if ($resultValidate['status'] !== 200) {
                return json_encode($resultValidate);
            }
            $params = $request->all();
            foreach ($params as $paramName => $param) {
                if (isset($param['name'])) {
                    $params[$paramName]['relationship'] = null;
                    if (isset($param['is_relation']) && (int)$param['is_relation']) {
                        $tableRalaionName = with(new $request->model_type)->{$param['name']}()->getRelated()->getTable();
                        $relationship = \DB::table($tableRalaionName)->pluck('name', 'id')->toArray();
                        $params[$paramName]['relationship'] = $relationship;
                    }
                }
            }
            unset($params['model_type']);
            unset($params['request_type']);
            $model = ExcelField::create([
                'model_type' => $request->model_type,
                'request_type' => $request->request_type,
                'data' => json_encode($params)
            ])->refresh();

            return json_encode([
                'message' =>  "<a href=\"" . route("virals.excel.download", $model->id) . "\">Download demo</a>",
                'status' => 200
            ]);
        }
    }

    private function validateRequest($request) {
        $fields = [];
        $params = $request->all();
        foreach ($params as $param) {
            if (isset($param['name'])) {
                $fields[] = $param['name'];
            }
        }
        $rules = with(new $request->request_type)->rules();
        $errors = [];
        foreach ($rules as $name => $rule) {
            if ((strpos($rule, 'required') !== false && !in_array($name, $fields)) || $fields == []) {
                $errors[] =  $name . " is required";
            }
        }
        if (count($errors)) {
            return[
                'message' =>  "<p style='color: red'><b>". implode(', ', $errors) . "</b></p>",
                'status' => 400
            ];
        }
        return [
            'message' =>  "",
            'status' => 200
        ];
    }

    public function downloadFile($id)
    {
        $export = new Export();
        return $export->processExport($id);
    }

}
