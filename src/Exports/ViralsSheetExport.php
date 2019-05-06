<?php

namespace ViralsBackpack\BackPackExcel\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class ViralsSheetExport implements FromCollection,WithTitle
{
    private $title;
    private $data;
    public function __construct($title, $data)
    {
        $this->title = $title;
        $this->data = $data;
    }
    public function collection()
    {
        return $this->data;
    }
    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }
}
