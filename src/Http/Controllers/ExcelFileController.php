<?php

namespace ViralsLaravel\ImportRelationExcel\Http\Controllers;

use ViralsLaravel\ImportRelationExcel\HandlExcel\Export;
use ViralsLaravel\ImportRelationExcel\Models\ExcelFile;
use ViralsLaravel\ImportRelationExcel\Models\ExcelField;
use Illuminate\Http\Request;

class ExcelFileController extends BaseExcelController
{
    public function __construct()
    {
        $this->model = ExcelFile::class;
        $this->crud['route_name'] = 'excel-files';
        $this->crud['columns'] = [
            ['name' => 'id'],
            ['name' => 'name'],
            ['name' => 'created_at'],
            ['name' => 'action', 'orderable' => false, 'searchable' => false]
        ];
        $this->crud['headers'] = [
            'Id',
            'Name',
            'Created at',
            'Action'
        ];
        view()->share('crud', $this->crud);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadFile($id)
    {
        $export = new Export();
        return $export->processExport($id);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {
        session(['file_excel_id' => $id]);
        return redirect()->route('excel-logs.index');
    }

    /**
     * @param Request $request
     * @return false|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|string
     */
    public function getRelatonColumn(Request $request)
    {
        if ($request->ajax()) {
            // validate
            if ($request->request_type) {
                $resultValidate = $this->validateRequest($request);
                if ($resultValidate['status'] !== 200) {
                    return response()->json(
                        $resultValidate['message']
                    , 422);
                }
            }
            // save model
            if ($request->model_type) {
                $params = $request->all();
                foreach ($params as $paramName => $param) {
                    if (isset($param['name'])) {
                        $params[$paramName]['relationship'] = null;
                        if (isset($param['is_relation']) && (int)$param['is_relation']) {
                            $tableRalaionName = with(new $request->model_type)->{$param['name']}()->getRelated()->getTable();
                            $params[$paramName]['relationship'] = \DB::table($tableRalaionName)->select(['id', $param['relationship_column_select']])->get();
                        }
                    }
                }
                unset($params['model_type']);
                unset($params['request_type']);
                unset($params['field_name']);
                if (count($params)) {
                    $model = ExcelField::create([
                        'name' => $request->field_name ?? "Field_" . date("Y-m-d h:i:sa"),
                        'model_type' => $request->model_type,
                        'request_type' => $request->request_type,
                        'data' => json_encode($params)
                    ])->refresh();
                    return response()->json([
                        'message' => "<a type=\"button\" class=\"btn btn-info\" href=\"" . route("excel-files.download", $model->id) . "\">Download demo</a>",
                    ], 200);
                }
                return response('Nothing create', 404);
            }
        }
        return response('Not found', 404);
    }

    /**
     * @param $request
     * @return array
     */
    private function validateRequest($request)
    {
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
                $errors[] = $name . " is required";
            }
        }
        if (count($errors)) {
            return [
                'message' => implode('<br>', $errors),
                'status' => 400
            ];
        }
        return [
            'message' => "",
            'status' => 200
        ];
    }
}
