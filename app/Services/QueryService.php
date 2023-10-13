<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Balance_organization;
use Illuminate\Database\Eloquent\Builder;


class QueryService
{

    public function smetaQuery($date, $organization)
{

         $balanceExist=Balance_organization::with('organization')
         ->where('date_balance', $date)
         ->whereHas('organization', function (Builder $query) use ($organization) {
            $query->where('organizations.full_name', 'like', $organization . '%');
         });


        $result = DB::table('balance_organizations')
            ->select(
                'organizations.full_name as organization',
                'nomenclatures.nomenclature_name as wood',
                'volume_wood_begin',
                'volume_wood_prihod',
                'volume_wood_prod',
                DB::raw('(volume_wood_begin + volume_wood_prihod - volume_wood_prod) as remainder')
            )
            ->join('organizations', 'balance_organizations.organization_id', '=', 'organizations.organization_id')
            ->join('nomenclatures', 'balance_organizations.nomenclature_id', '=', 'nomenclatures.nomenclature_id')
            ->join('wood_group_types', 'balance_organizations.wood_group_type_id', '=', 'wood_group_types.wood_group_type_id')
            ->where('balance_organizations.date_balance', '=', $date)
            ->where('organizations.full_name', 'like', $organization . '%')
            ->union(function ($query) use ($organization, $date) {
                $query->select(
                    'organizations.full_name as organization',
                    DB::raw("CONCAT('Всего по ', organizations.full_name) as wood"),
                    DB::raw('SUM(volume_wood_begin) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prihod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_begin + volume_wood_prihod - volume_wood_prod) as remainder')
                )
                    ->from('balance_organizations')
                    ->join('organizations', 'balance_organizations.organization_id', '=', 'organizations.organization_id')
                    ->join('nomenclatures', 'balance_organizations.nomenclature_id', '=', 'nomenclatures.nomenclature_id')
                    ->join('wood_group_types', 'balance_organizations.wood_group_type_id', '=', 'wood_group_types.wood_group_type_id')
                    ->where('balance_organizations.date_balance', '=', $date)
                    ->groupBy('organizations.full_name')
                    ->where('organizations.full_name', 'like', $organization . '%');
            })
            ->union(function ($query) use ($organization, $date) {
                $query->select(
                    'organizations.full_name as organization',
                    'wood_group_types.full_name as wood',
                    DB::raw('SUM(volume_wood_begin) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prihod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_begin + volume_wood_prihod - volume_wood_prod) as remainder')
                )
                    ->from('balance_organizations')
                    ->join('organizations', 'balance_organizations.organization_id', '=', 'organizations.organization_id')
                    ->join('nomenclatures', 'balance_organizations.nomenclature_id', '=', 'nomenclatures.nomenclature_id')
                    ->join('wood_group_types', 'balance_organizations.wood_group_type_id', '=', 'wood_group_types.wood_group_type_id')
                    ->where('balance_organizations.date_balance', '=', $date)
                    ->groupBy('organization', 'wood')
                    ->where('organizations.full_name', 'like', $organization . '%');
            })
            ->union(function ($query) use ($date) {
                $query->select(
                    DB::raw("'ВСЕГО ПО ОТЧЕТУ' as organization"),
                    DB::raw("'' as wood"),
                    DB::raw('SUM(volume_wood_begin) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prihod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_begin + volume_wood_prihod - volume_wood_prod) as remainder')
                )
                    ->from('balance_organizations')
                    ->join('organizations', 'balance_organizations.organization_id', '=', 'organizations.organization_id')
                    ->where('balance_organizations.date_balance', '=', $date);
            })
            ->orderBy('organization', 'DESC')
            ->get();

            //Проверяем вырбрали ли мы организацию
            if($organization!=''){
                $result = DB::table('balance_organizations')
            ->select(
                'organizations.full_name as organization',
                'nomenclatures.nomenclature_name as wood',
                'volume_wood_begin',
                'volume_wood_prihod',
                'volume_wood_prod',
                DB::raw('(volume_wood_begin + volume_wood_prihod - volume_wood_prod) as remainder')
            )
            ->join('organizations', 'balance_organizations.organization_id', '=', 'organizations.organization_id')
            ->join('nomenclatures', 'balance_organizations.nomenclature_id', '=', 'nomenclatures.nomenclature_id')
            ->join('wood_group_types', 'balance_organizations.wood_group_type_id', '=', 'wood_group_types.wood_group_type_id')
            ->where('balance_organizations.date_balance', '=', $date)
            ->where('organizations.full_name', 'like', $organization . '%')
            ->union(function ($query) use ($organization, $date) {
                $query->select(
                    'organizations.full_name as organization',
                    DB::raw("CONCAT('Всего по ', organizations.full_name) as wood"),
                    DB::raw('SUM(volume_wood_begin) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prihod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_begin + volume_wood_prihod - volume_wood_prod) as remainder')
                )
                    ->from('balance_organizations')
                    ->join('organizations', 'balance_organizations.organization_id', '=', 'organizations.organization_id')
                    ->join('nomenclatures', 'balance_organizations.nomenclature_id', '=', 'nomenclatures.nomenclature_id')
                    ->join('wood_group_types', 'balance_organizations.wood_group_type_id', '=', 'wood_group_types.wood_group_type_id')
                    ->where('balance_organizations.date_balance', '=', $date)
                    ->groupBy('organizations.full_name')
                    ->where('organizations.full_name', 'like', $organization . '%');
            })
            ->union(function ($query) use ($organization, $date) {
                $query->select(
                    'organizations.full_name as organization',
                    'wood_group_types.full_name as wood',
                    DB::raw('SUM(volume_wood_begin) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prihod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_begin + volume_wood_prihod - volume_wood_prod) as remainder')
                )
                    ->from('balance_organizations')
                    ->join('organizations', 'balance_organizations.organization_id', '=', 'organizations.organization_id')
                    ->join('nomenclatures', 'balance_organizations.nomenclature_id', '=', 'nomenclatures.nomenclature_id')
                    ->join('wood_group_types', 'balance_organizations.wood_group_type_id', '=', 'wood_group_types.wood_group_type_id')
                    ->where('balance_organizations.date_balance', '=', $date)
                    ->groupBy('organization', 'wood')
                    ->where('organizations.full_name', 'like', $organization . '%');
            })
            ->union(function ($query) use ($organization, $date) {
                $query->select(
                    DB::raw("'ВСЕГО ПО ОТЧЕТУ' as organization"),
                    DB::raw("'' as wood"),
                    DB::raw('SUM(volume_wood_begin) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prihod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_begin + volume_wood_prihod - volume_wood_prod) as remainder')
                )
                    ->from('balance_organizations')
                    ->join('organizations', 'balance_organizations.organization_id', '=', 'organizations.organization_id')
                    ->where('balance_organizations.date_balance', '=', $date)
                    ->where('organizations.full_name', 'like', $organization. '%');
            })
            ->orderBy('organization', 'DESC')
            ->get();

        }
            
        //Проверяем есть ли такая организация по имени еслт нет то проверяем по УНП
        if ($balanceExist->count()==0) {
            $balanceExist=Balance_organization::with('organization')
            ->where('date_balance', $date)
            ->whereHas('organization', function (Builder $query) use ($organization) {
               $query->where('organizations.unp', 'like', $organization . '%');
            });
            $result = DB::table('balance_organizations')
            ->select(
                'organizations.full_name as organization',
                'nomenclatures.nomenclature_name as wood',
                'volume_wood_begin',
                'volume_wood_prihod',
                'volume_wood_prod',
                DB::raw('(volume_wood_begin + volume_wood_prihod - volume_wood_prod) as remainder')
            )
            ->join('organizations', 'balance_organizations.organization_id', '=', 'organizations.organization_id')
            ->join('nomenclatures', 'balance_organizations.nomenclature_id', '=', 'nomenclatures.nomenclature_id')
            ->join('wood_group_types', 'balance_organizations.wood_group_type_id', '=', 'wood_group_types.wood_group_type_id')
            ->where('balance_organizations.date_balance', '=', $date)
            ->where('organizations.full_name', 'like', $organization . '%')
            ->union(function ($query) use ($organization, $date) {
                $query->select(
                    'organizations.full_name as organization',
                    DB::raw("CONCAT('Всего по ', organizations.full_name) as wood"),
                    DB::raw('SUM(volume_wood_begin) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prihod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_begin + volume_wood_prihod - volume_wood_prod) as remainder')
                )
                    ->from('balance_organizations')
                    ->join('organizations', 'balance_organizations.organization_id', '=', 'organizations.organization_id')
                    ->join('nomenclatures', 'balance_organizations.nomenclature_id', '=', 'nomenclatures.nomenclature_id')
                    ->join('wood_group_types', 'balance_organizations.wood_group_type_id', '=', 'wood_group_types.wood_group_type_id')
                    ->where('balance_organizations.date_balance', '=', $date)
                    ->groupBy('organizations.full_name')
                    ->where('organizations.full_name', 'like', $organization . '%');
            })
            ->union(function ($query) use ($organization, $date) {
                $query->select(
                    'organizations.full_name as organization',
                    'wood_group_types.full_name as wood',
                    DB::raw('SUM(volume_wood_begin) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prihod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_begin + volume_wood_prihod - volume_wood_prod) as remainder')
                )
                    ->from('balance_organizations')
                    ->join('organizations', 'balance_organizations.organization_id', '=', 'organizations.organization_id')
                    ->join('nomenclatures', 'balance_organizations.nomenclature_id', '=', 'nomenclatures.nomenclature_id')
                    ->join('wood_group_types', 'balance_organizations.wood_group_type_id', '=', 'wood_group_types.wood_group_type_id')
                    ->where('balance_organizations.date_balance', '=', $date)
                    ->groupBy('organization', 'wood')
                    ->where('organizations.full_name', 'like', $organization . '%');
            })
            ->union(function ($query) use ($date) {
                $query->select(
                    DB::raw("'ВСЕГО ПО ОТЧЕТУ' as organization"),
                    DB::raw("'' as wood"),
                    DB::raw('SUM(volume_wood_begin) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prihod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_begin + volume_wood_prihod - volume_wood_prod) as remainder')
                )
                    ->from('balance_organizations')
                    ->join('organizations', 'balance_organizations.organization_id', '=', 'organizations.organization_id')
                    ->where('balance_organizations.date_balance', '=', $date);
            })
            ->orderBy('organization', 'DESC')
            ->get();
           //Проверяем выбрана ли органияция для вычисления общего значения для выбранной организации
            if($organization!=''){
            $result = DB::table('balance_organizations')
            ->select(
                'organizations.full_name as organization',
                'nomenclatures.nomenclature_name as wood',
                'volume_wood_begin',
                'volume_wood_prihod',
                'volume_wood_prod',
                DB::raw('(volume_wood_begin + volume_wood_prihod - volume_wood_prod) as remainder')
            )
            ->join('organizations', 'balance_organizations.organization_id', '=', 'organizations.organization_id')
            ->join('nomenclatures', 'balance_organizations.nomenclature_id', '=', 'nomenclatures.nomenclature_id')
            ->join('wood_group_types', 'balance_organizations.wood_group_type_id', '=', 'wood_group_types.wood_group_type_id')
            ->where('balance_organizations.date_balance', '=', $date)
            ->where('organizations.unp', 'like', $organization . '%')
            ->union(function ($query) use ($organization, $date) {
                $query->select(
                    'organizations.full_name as organization',
                    DB::raw("CONCAT('Всего по ', organizations.full_name) as wood"),
                    DB::raw('SUM(volume_wood_begin) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prihod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_begin + volume_wood_prihod - volume_wood_prod) as remainder')
                )
                    ->from('balance_organizations')
                    ->join('organizations', 'balance_organizations.organization_id', '=', 'organizations.organization_id')
                    ->join('nomenclatures', 'balance_organizations.nomenclature_id', '=', 'nomenclatures.nomenclature_id')
                    ->join('wood_group_types', 'balance_organizations.wood_group_type_id', '=', 'wood_group_types.wood_group_type_id')
                    ->where('balance_organizations.date_balance', '=', $date)
                    ->groupBy('organizations.full_name')
                    ->where('organizations.unp', 'like', $organization . '%');
            })
            ->union(function ($query) use ($organization, $date) {
                $query->select(
                    'organizations.full_name as organization',
                    'wood_group_types.full_name as wood',
                    DB::raw('SUM(volume_wood_begin) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prihod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_begin + volume_wood_prihod - volume_wood_prod) as remainder')
                )
                    ->from('balance_organizations')
                    ->join('organizations', 'balance_organizations.organization_id', '=', 'organizations.organization_id')
                    ->join('nomenclatures', 'balance_organizations.nomenclature_id', '=', 'nomenclatures.nomenclature_id')
                    ->join('wood_group_types', 'balance_organizations.wood_group_type_id', '=', 'wood_group_types.wood_group_type_id')
                    ->where('balance_organizations.date_balance', '=', $date)
                    ->groupBy('organization', 'wood')
                    ->where('organizations.unp', 'like', $organization . '%');
            })
            ->union(function ($query) use ($organization, $date) {
                $query->select(
                    DB::raw("'ВСЕГО ПО ОТЧЕТУ' as organization"),
                    DB::raw("'' as wood"),
                    DB::raw('SUM(volume_wood_begin) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prihod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_prod) as volume_wood_begin'),
                    DB::raw('SUM(volume_wood_begin + volume_wood_prihod - volume_wood_prod) as remainder')
                )
                    ->from('balance_organizations')
                    ->join('organizations', 'balance_organizations.organization_id', '=', 'organizations.organization_id')
                    ->where('balance_organizations.date_balance', '=', $date)
                    ->where('organizations.unp', 'like', $organization. '%');
            })
            ->orderBy('organization', 'DESC')
            ->get();
            }
            }
    
        if ($balanceExist->count()==0) {
            return response()->json(['message' => 'Сметы на эту дату нет'], 404);
        } else {
            
            return json_decode($result);
        }
}
}
