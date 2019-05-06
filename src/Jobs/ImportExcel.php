<?php

namespace ViralsBackpack\BackPackExcel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use ViralsBackpack\BackPackExcel\Models\ExcelField;
use ViralsBackpack\BackPackExcel\Models\ExcelFile;
use ViralsBackpack\BackPackExcel\Models\ExcelFileLog;

class ImportExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $data;
    protected $excelFile;
    protected $fieldId;
    protected $excelField;
    protected $relationData;
    protected $error;


    public function __construct($data, $excelFile)
    {
        $this->data = $data;
        $this->excelFile = $excelFile;
    }

    /**
     * Execute the job.
     *
     * @return boolean
     */
    public function handle()
    {
        try {
            $reader = $this->data->toArray();
            // check exist data in sheet 1 and exist field id
            if (empty($reader) || empty($reader[0]) || empty($reader[0]['field_id'])) {
                // write log when error
                $this->writeLog(trans('excel.row'), trans('excel.file_data_not_found'));
                throw new \Exception('Process have some errors, check logs file');
            }

            //handle excel
            $this->handleExcel($reader);

            //update status file
            $this->excelFile->update(['status' => ExcelFile::PROCESSED]);

            //save log
            $this->saveLog();
        } catch (\Exception $e) {
            $this->saveLog();
            return $e->getMessage();
        }
    }

    /**
     * handle import excel
     * @param $reader
     */
    private function handleExcel($reader)
    {
        $j = -1;
        foreach ($reader as $val) {
            $j++;
            if (!$this->setupAttribute($val, ($j + 2))) continue;
            unset($val['field_id']);
            $data = $this->getDataByColumn($val, ($j + 2));
            if (!$data) continue;
            $err = $this->validateForm($data);
            if (!empty($err)) {
                $this->writeLog(trans('excel.row') . ' ' . ($j + 2), implode("<br>", $err));
            } else {
                //create record
                $model = $this->excelField->model_type;
                if ($model) {
                    //create model
                    $model = $model::create($data);

                    //sync relationship
                    $this->syncRelationship($model, $val, $j + 2);
                }
            }
        }
    }

    private function setupAttribute($value, $row)
    {
        if (!$this->setField($value['field_id'], $row)) {
            return false;
        };

        //set relationship
        if (!$this->setRelationData($row)) {
            return false;
        };
        return true;
    }

    private function saveLog()
    {
        // write log
        if (isset($this->error)) {
            ExcelFileLog::insert($this->error);
        }
    }

    /**
     * set field attr
     * @param $fieldId
     */
    private function setField($fieldId, $row)
    {
        $this->excelField = ExcelField::find($fieldId);
        if (!$this->excelField) {
            $this->writeLog($row, trans('excel.field_record_not_found') . ', field id:  ' . $fieldId);
            return false;
        }
        return true;
    }

    /**
     * sync relationship
     * @param $model
     * @param $val
     */
    private function syncRelationship($model, $val, $row)
    {
        if (!empty($this->relationData)) {
            foreach ($this->relationData as $reKey => $reValue) {
                $reClass = get_class($model->{$reValue['method']}()->getRelated());
                $relationData = $reClass::query()
                    ->where($reValue['column_name'], $val[$reKey])
//                    ->whereRaw("cast(? as char(255)) = ?", $reValue['column_name'], $val[$reKey])
//                    ->orWhereRaw("cast(id as char(255)) = ?", $val[$reKey])
                    ->first();
                if ($relationData) {
                    switch ($reValue['type']) {
                        case 'BelongsTo':
                            $model->{$reValue['method']}()->associate($relationData);
                            $model->save();
                            break;
                        case 'BelongsToMany':
                            $model->{$reValue['method']}()->sync($relationData->id);
                            break;
//                        case 'HasMany':
//                            $model->{$reValue['method']}()->sync($val[$reKey]);
//                            break;
                        default:
                    }
                } else {
                    $this->writeLog($row, 'No record of class ' . $reClass .' has key ' . $val[$reKey]);
                }
            }
        }
    }

    /**
     * get data allow column
     * @param $value
     * @return array|bool
     */
    private function getDataByColumn($value, $row)
    {
        $dataArrs = json_decode($this->excelField->data, true);
        $data = [];
        foreach ($value as $k => $v) {
            if (empty($dataArrs[$k])) {
                $this->writeLog($row, "Data excel don't match with data field");
                return false;
            }
            $data[$dataArrs[$k]['name']] = $v;
        }
        return $data;
    }

    /**
     * write log
     * @param $row
     * @param $log
     * @return mixed
     */
    private function writeLog($row, $log)
    {
        $this->error[] = array(
            'file_id' => $this->excelFile->id,
            'row' => $row,
            'note' => $log,
        );
    }

    /**
     * set relationship attr
     * @return array
     */
    private function setRelationData($row)
    {
        $columnData = json_decode($this->excelField->data, true);
        $relationData = [];
        if (!$columnData) {
            $this->writeLog($row, 'No data in field, field id: ' . $this->excelField->id);
            return null;
        }
        foreach ($columnData as $key => $value) {
            if ($value['is_relation']) {
                $relationData[$key]['method'] = $value['name'];
                $relationData[$key]['type'] = $value['relationship_type'];
                $relationData[$key]['column_name'] = $value['relationship_column_select'];
            }
        }
        $this->relationData = $relationData;
        return $relationData;
    }

    /**
     * validate data
     * @param $request
     * @return array|string
     */
    public function validateForm($request)
    {
        $field = $this->excelField;
        $requestType = $field->request_type;

        $requestType = new $requestType;
        $rules = $requestType->rules();
        $messages = $requestType->messages();
        $attributes = $requestType->attributes();
        $validator = \Validator::make($request, $rules, $messages, $attributes);
        if ($validator->fails()) {
            $error = array();
            $key = array_keys($request);
            foreach ($key as $k) {
                if (!empty($validator->errors()->get($k)[0])) {
                    $error[$k] = $validator->errors()->get($k)[0];
                }
            }
            return $error;
        }
        return '';
    }
}