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
        Schema::create("booking", function (Blueprint $table) {
            $table->id();
            $table->date("startDate");
            $table->date("endDate");
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("apartments_id");
            //$table->foreignId("user_id")->constrained('user')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            //$table->foreignId("apartments_id")->constrained()->cascadeOnDelete();
            $table->foreign('apartments_id')->references('id')->on('apartments')->onDelete('cascade');
            $table->timestamps();


    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("booking");
    }
};
