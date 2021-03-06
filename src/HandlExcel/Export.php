<?php

namespace ViralsLaravel\ImportRelationExcel\HandlExcel;

use ViralsLaravel\ImportRelationExcel\Exports\ViralsExport;
use ViralsLaravel\ImportRelationExcel\Models\ExcelField;

class Export
{
    protected $field;
    protected $relationShip = [];
    protected $sheet;

    /**
     * @param $id
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function processExport($id)
    {
        $fields = ExcelField::find($id);
        if (!is_null($fields)) {
            $data = json_decode($fields->data, true);
            $dataExport = [];
            $headModel = [];
            foreach ($data as $fieldName => $field) {
                if ((int)$field['is_relation'] && count($field['relationship'])) {
                    $dataExport['relationship'][$field['label']][] = collect(array_keys($field['relationship'][0]));
                    foreach ($field['relationship'] as $key => $item) {
                        $dataExport['relationship'][$field['label']][] = collect($item);
                    }
                    $dataExport['relationship'][$field['label']] = collect($dataExport['relationship'][$field['label']]);
                }
                $headModel[] = $field['label'];
            }
            $headModel[] = 'field_id';
            $dataExport['model'][] = $headModel;
            $temp = array_fill(0, count($headModel) - 1, '');
            array_push($temp, $id);
            $dataExport['model'][] = $temp;
            return (new ViralsExport($dataExport))->download($fields->name . '.xlsx');
        }
    }
}
