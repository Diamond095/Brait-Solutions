<?php

use App\Exports\SmetaExport;
use App\Http\Controllers\DownloadSmetaController;
use App\Http\Controllers\GetOrganizationController;
use App\Http\Controllers\GetSmetaForSelectedDateController;
use App\Http\Controllers\GetSmetaToday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/organizations',GetOrganizationController::class);
Route::get('/smetatoday',GetSmetaToday::class);
Route::post('/smetaforselecteddate', GetSmetaForSelectedDateController::class);
Route::post('/download/smeta', DownloadSmetaController::class);
Route::get('/download/{date}/{organization?}', function($date,$organization=''){
    return Excel::download(new SmetaExport($date, $organization), 'smeta.xlsx');
});
