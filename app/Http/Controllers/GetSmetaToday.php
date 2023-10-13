<?php

namespace App\Http\Controllers;

use  App\Models\Balance_organization;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GetSmetaToday extends Controller
{
    public function __invoke(){
        $today = date("Y.m.d");
        $smetaToday=Balance_organization::with('organization', 'nomenclature', 'woodGroupType')->where('date_balance', $today)->get();
        return json_decode($smetaToday);
    }

}
