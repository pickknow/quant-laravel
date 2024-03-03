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
        Schema::create('industries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rank')->nullable();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->float('price')->nullable();
            $table->float('up_price')->nullable();
            $table->string('up_by')->nullable();
            $table->bigInteger('tmc')->nullable();
            $table->float('turnover_rate')->nullable();
            $table->bigInteger('ups')->nullable();
            $table->bigInteger('downs')->nullable();
            $table->string('leader_stock')->nullable();
            $table->float('leader_by')->nullable();
            $table->text('nums')->nullable();
            $table->text('nums_names')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('industries');
    }
};
