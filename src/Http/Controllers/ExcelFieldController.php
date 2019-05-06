<?php

namespace ViralsLaravel\ImportRelationExcel\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use ViralsLaravel\ImportRelationExcel\Models\ExcelField;
use Illuminate\Container\Container;

class ExcelFieldController extends BaseExcelController
{
    public function __construct()
    {
        $this->model = ExcelField::class;
        $this->crud['route_name'] = 'excel-fields';
        $this->crud['columns'] = [
            ['name' => 'id'],
            ['name' => 'name'],
            ['name' => 'model_type'],
            ['name' => 'created_at'],
            ['name' => 'action', 'orderable' => false, 'searchable' => false]
        ];
        $this->crud['headers'] = [
            'Id',
            'Name',
            'Model type',
            'Create at',
            'Action'
        ];
        $this->crud['stack_top'] = ['create'];
        view()->share('crud', $this->crud);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $entry = $this->model::find($id);
        if ($entry) {
            return response()->json(json_decode($entry->data),200,[],JSON_PRETTY_PRINT);
        }
        return response()->json([]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $allModels= $this->getAllModels();
        $viewModels = [];
        foreach ($allModels as $model) {
            if (class_exists($model)) {
                $viewModels[(new $model)->getTable()] = $model;
            }
        }
        return view('viralslaravelexcel::contents.create_field', ['models' => $viewModels]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function getAllModels()
    {
        $appNamespace = Container::getInstance()->getNamespace();
        $modelNamespace = 'Models';

        $models = collect(\File::allFiles(app_path($modelNamespace)))->map(function ($item) use ($appNamespace, $modelNamespace) {
            $rel   = $item->getRelativePathName();
            $class = sprintf('\%s%s%s', $appNamespace, $modelNamespace ? $modelNamespace . '\\' : '',
                implode('\\', explode('/', substr($rel, 0, strrpos($rel, '.')))));
            return class_exists($class) ? $class : null;
        })->filter();

        return $models;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function getColumns(Request $request)
    {
        if ($request->ajax()) {
            $tableName = $request->table ?? '';
            $modelName = $request->model ?? '';
            $model = new $modelName;

            $dataRelation = [];
            $fKeyNames = [];
            $relations = $model->relationships();
            foreach ($relations as $relation => $infor) {
                if (in_array($infor['type'], ['BelongsTo', 'BelongsToMany'])) {
                    $columnsRelation = \DB::connection()->getSchemaBuilder()->getColumnListing((new $infor['model'])->getTable());
                    if ($infor['type'] == 'BelongsTo') {
                        $fKeyNames[] = method_exists($model->$relation(),'getForeignKeyName') ?
                            $model->$relation()->getForeignKeyName():
                            $model->$relation()->getForeignKey();
                    }
                    $dataRelation[] = array(
                        'name' => $relation,
                        'is_relation' => 1,
                        'columns' => $columnsRelation,
                        'relationship_type' => $infor['type']
                    );
                }
            }

            $dataModel = [];
            $columns = \DB::connection()->getSchemaBuilder()->getColumnListing($tableName);
            foreach ($columns as $column) {
                if (!in_array($column, $fKeyNames) && $column !== 'id') {
                    $dataModel[] = array(
                        'name' => $column,
                        'is_relation' => 0,
                        'relationship_type' => ''
                    );
                }
            }
            $data['template'] = array_merge($dataModel, $dataRelation);
            $data['model_type'] = $modelName;
            $data['request_type'] = @with(new $modelName)->requestExcel;
            return response()->json($data, 200);
        }
        return response('Not found data', 404);
    }
}
