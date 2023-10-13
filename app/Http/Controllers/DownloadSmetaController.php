<?php

namespace App\Http\Controllers;

use App\Exports\SmetaExport;
use App\Http\Requests\ReportRequest;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Balance_organization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;




class DownloadSmetaController extends Controller
{
    public function __invoke(ReportRequest $request){
     $data = $request->validated();

    }
}
