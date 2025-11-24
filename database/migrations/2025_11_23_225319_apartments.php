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
        Schema::create("apartments", function (Blueprint $table) {
            $table->id();
           
            $table->string("title");
            $table->string("description")->nullable();
            $table->float("price");
            $table->enum("status", ["available","unavailable"])->default("available");
            $table->foreignId("user_id")->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger("city_id");            
            $table->json('images')->nullable();
            $table->timestamps();
            $table->foreign('city_id')->references('id')->on('city')->onDelete('cascade');
           


    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("apartments");
    }
};
