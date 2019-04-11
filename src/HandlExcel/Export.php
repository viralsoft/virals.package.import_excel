<?php

namespace ViralsBackpack\BackPackExcel\HandlExcel;

use ViralsBackpack\BackPackExcel\Exports\ViralsExport;
use ViralsBackpack\BackPackExcel\Models\ExcelField;

class Export
{
    protected $field;
    protected $relationShip = [];
    protected $sheet;

    public function processExport($id)
    {
        $fields = ExcelField::find($id);
        $data = json_decode($fields->data, true);
        $dataExport = [];
        if (!is_null($fields)) {
            $headModel = [];
            foreach ($data as $fieldName => $field) {
                if ((int)$field['is_relation']) {
                    $dataExport['relationship'][$field['label']][] = collect(['id', $field['label']]);

                    foreach ($field['relationship'] as $key => $item) {
                        $dataExport['relationship'][$field['label']][] = collect([
                            $key,
                            $item
                        ]);
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
            return (new ViralsExport($dataExport))->download('field_' . $id . '.xlsx');
        }
    }
}