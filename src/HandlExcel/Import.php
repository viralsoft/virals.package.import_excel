<?php

namespace ViralsLaravel\ImportRelationExcel\HandlExcel;

use Maatwebsite\Excel\Facades\Excel;
use ViralsLaravel\ImportRelationExcel\Models\ExcelFile;
use Illuminate\Support\Facades\Storage;
use ViralsLaravel\ImportRelationExcel\Imports\UsersImport;
use Carbon\Carbon;

class Import
{
    /**
     * @param array $params
     * @return bool
     */
    public function processImport($file){
        $response = $this->upload($file);
        if(!$response)
        {
            $data = array(
                'name' => '',
                'status' => ExcelFile::ERROR,
                'url'=> '',
            );
            ExcelFile::create($data);
            return 'error';
        }
        $data = array(
            'name' => $response['file_name'],
            'status' => ExcelFile::PROCESSING,
            'url'=> $response['url'],
        );
        //create status file
        $excelFile = ExcelFile::create($data);
        //import data by excel
        Excel::import(new UsersImport($excelFile), $excelFile->url);
        return response()->json('warning');
    }

    /*
     * Function Upload file import excel
     *
     * @param: $file
     *
     * return array(url, file_name)
     * */
    public function upload($file)
    {
        if(!$file)
        {
            return false;
        }
        $file_name = $file->getClientOriginalName();
        $extension = explode('.', $file_name);
        $file_name = $extension[0];
        $extension = $extension[count($extension) - 1];
        $file_name = 'TemplateExcel_';
        $path = 'public/upload/excel/' . date("Y/m");
        $private_name =  preg_replace('/\s+/', '-', $file_name . Carbon::now()->toDateTimeString() . '.' . $extension);
        Storage::putFileAs($path, $file, $private_name);
        $path_full = str_replace('public/', '', $path);
        $data = array(
            'url'       => 'storage/' . $path_full . '/' . $private_name,
            'file_name' => $private_name
        );
        return $data;
    }
}