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
        Schema::create('sectors_links', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sector_id')->unsigned();
            $table->string('link', 255);
            $table->string('description', 255);
            $table->bigInteger('permission_id')->unsigned();
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->foreign('sector_id')
                ->references('id')
                ->on('sectors')
                ->onDelete('cascade');

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sectors_links');
    }
};
