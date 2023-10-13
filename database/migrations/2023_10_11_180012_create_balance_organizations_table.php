<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('balance_organizations', function (Blueprint $table) {
            $table->id('balance_id');
            $table->foreignId('wood_group_type_id')->index()->constrained('wood_group_types', 'wood_group_type_id');
            $table->foreignId('nomenclature_id')->index('nomenclature_id')->constrained('nomenclatures','nomenclature_id');
            $table->foreignId('organization_id')->index('organization_id')->constrained('organizations', 'organization_id');
            $table->date('date_balance');
            $table->decimal('volume_wood_begin',15, 3);
            $table->decimal('volume_wood_prihod',15, 3);
            $table->decimal('volume_wood_prod',15, 3);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_organizations');
    }
};
