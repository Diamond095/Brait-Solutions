<?php

namespace App\Exports;

use App\Models\Balance_organization;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Services\QueryService;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithHeadings;


class SmetaExport implements FromCollection, WithHeadings

{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $date;
    public $organization;
    public function __construct($date, $organization)
    {
        $this->date = $date;
        $this->organization = $organization;
    }
    public function collection()
    {
        $service=new QueryService();
        return collect($service->smetaQuery($this->date, $this->organization));
    }
    public function headings(): array
    {
        return [
            'Организация',
            'Порода древесины/Номенклатура',
            'Остаток на начало',
            'Поступило на период',
            'Переработка',
            'Остаток на конец'

        ];
    }
    
    
}


