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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('company_name', 255);
            $table->string('address', 255);
            $table->string('address_number', 255);
            $table->string('address_complement', 255)->nullable();
            $table->string('neighborhood', 255)->nullable();
            $table->string('city', 255);
            $table->string('state', 2);
            $table->string('zip_code', 9);
            $table->string('landline', 20)->nullable();
            $table->string('cell_phone', 20)->nullable();
            $table->string('email', 255)->unique();
            $table->string('slug', 255)->unique();
            $table->string('abbr', 3)->unique();
            $table->text('map', 1000)->nullable();
            $table->text('mini_map', 1000)->nullable();
            $table->string('coords', 255)->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
