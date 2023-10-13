<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\Balance_organization;
use Illuminate\Database\Eloquent\Builder;
use App\Services\QueryService;

class GetSmetaForSelectedDateController extends Controller
{
    public function __invoke(ReportRequest $request, QueryService $service)
    {
        $data = $request->validated();
        return $service->smetaQuery( $data['date'],$data['organization']);
    }
}
