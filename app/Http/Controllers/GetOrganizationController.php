<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class GetOrganizationController extends Controller
{
    public function  __invoke()
    {
        //Выбираем все записи
        $organizations = Organization::all();
        //Сортируем
        $sortOrganizations=$organizations->sortBy('full_name');
        return json_decode($sortOrganizations->values());
    }
}
