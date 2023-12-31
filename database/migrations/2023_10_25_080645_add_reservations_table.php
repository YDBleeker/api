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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->Integer('sport_article_id');
            $table->integer('count');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('course');
            $table->boolean('confirmed')->nullable()->default(false);
            $table->boolean('lent')->nullable()->default(false);
            $table->boolean('history')->nullable()->default(false);
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
