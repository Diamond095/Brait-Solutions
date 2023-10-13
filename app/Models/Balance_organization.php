<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wood_group_type;
use App\Models\Nomenclature;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Balance_organization extends Model
{
    use HasFactory;

    public function woodGroupType() : BelongsTo
    {
        return $this->belongsTo(Wood_group_type::class, 'wood_group_type_id','wood_group_type_id');
    }

    public function organization() : BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id','organization_id');
    }

    public function nomenclature() : BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'nomenclature_id', 'nomenclature_id');
    }

}
