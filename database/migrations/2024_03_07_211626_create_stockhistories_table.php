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
        Schema::create('stockhistories', function (Blueprint $table) {
            $table->id();
            $table->string('date')->nullable();
            $table->float('open')->default(0);
            $table->float('close')->default(0);
            $table->float('high')->default(0);
            $table->float('low')->default(0);
            $table->bigInteger('vol')->default(0);
            $table->bigInteger('amt')->default(0);
            $table->float('amplitude')->default(0);
            $table->float('quote_change')->default(0);
            $table->float('changes')->default(0);
            $table->float('ratio')->default(0);
            $table->float('ma5')->default(0); // the ma5 of the last day of the stock.
            $table->float('ma20')->default(0);
            $table->float('ma60')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stockhistories');
    }
};
